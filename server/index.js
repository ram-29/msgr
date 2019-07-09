const url = require('url')
const http = require('http')
const path = require('path')
const cors = require('cors')
const https = require('https')
const fs = require('fs-extra')
const axios = require('axios')
const uuid = require('uuid/v4')
const morgan = require('morgan')
const moment = require('moment')
const express = require('express')
const webPush = require('web-push')
const socket = require('socket.io')
const bodyParser = require('body-parser')
const errorhandler = require('errorhandler')
const siofu = require('socketio-file-upload')
const thumb = require('node-thumbnail').thumb
const sharedsession = require('express-socket.io-session')

const pubVapidKey = 'BM_rgVMC88LMFjWGiTQOHVKUF4W7An0fT_2k9Z60AQYxH656dcRwyeFQ7vZRo6sGNPyQlNKksPHdgvNZWWuqjTQ'
const priVapidKey = 'X50nQm5JEHSGxGJ31mfMcuWyQy4yjnwxFV6TsYbjqqs'

webPush.setVapidDetails('mailto:admin@msgr.io', pubVapidKey, priVapidKey)       

const session = require('express-session')({ 
    secret: '$2a$07$sXmjRkhWZxKDsudVk281X.Y.RLqgzlfysiBOfXizwLLzea7IbUGWG', 
    cookie: { maxAge: 60000 },
    resave: true,
    saveUninitialized: true
})

const app = express()

app.use(cors())
app.use(session)
app.use(siofu.router)
app.use(errorhandler())
app.use(bodyParser.json())
app.use(morgan('combined'))
app.use(bodyParser.urlencoded({ extended: false }))

const httpServer = http.createServer(app)
const httpsServer = https.createServer({ key: '', cert: '' }, app)

// Change `BASE_URLS` on production.
let BK_HTTP_URL = `http://localhost:80/msgr/backend/web`
let FR_HTTP_URL = `http://localhost:80/msgr/frontend/web`

let BK_HTTPS_URL = `https://localhost:443/msgr/backend/web`
let FR_HTTPS_URL = `https://localhost:443/msgr/frontend/web`

const HTTP_PORT = 1337
const HTTPS_PORT = 7331

httpServer.listen(HTTP_PORT, _ => { console.log(`Http server running on port ${HTTP_PORT}.`)})
httpsServer.listen(HTTPS_PORT, _ => { console.log(`Https server running on port ${HTTPS_PORT}.`) })

// Initialize socket connection.
initConn(httpServer)
// initConn(httpsServer)

function initConn(mServer) {
    const io = socket.listen(mServer)

    // Custom server code
    io.on('connection', mSocket => {
        
        let USER_ID = {}
        let DISPLAY_NAME = {}

        const updateActiveUsers = _ => io.sockets.emit('online-users', Object.keys(DISPLAY_NAME))

        mSocket.on('set-user', (data, cb) => {
            if (data.user_id in USER_ID) {
                cb(false)
                mSocket.user_id = data.user_id
                mSocket.name = data.name
            } else {
                cb(true)
                mSocket.user_id = data.user_id
                mSocket.name = data.name

                USER_ID[mSocket.user_id] = mSocket
                DISPLAY_NAME[mSocket.name] = mSocket.name

                updateActiveUsers()
            }
        })

    })

    /////------- Private Messaging -------/////

    io.of('/simple')
        .use(sharedsession(session, { autoSave: true }))
        .on('connection', simple => {

        // User id & name.
        const { id, name } = simple.handshake.query
        console.log(`${name} has connected to PM.`)

        // File Upload listener
        const sUploader = new siofu()
        sUploader.dir = '../frontend/web/files';
        sUploader.listen(simple)

        sUploader.on('start', event => {
            if (/\.exe$/.test(event.file.name)) {
                console.log(`Aborting: ${event.file.id}`)
                sUploader.abort(event.file.id, simple)
            }
        })

        sUploader.on('saved', event => {
            // Filename.
            let fName = path.basename(event.file.pathName)
            let mName = `${uuid() + path.parse(fName).ext}`

            // Create Directory.
            let mDir = `../frontend/web/files/${event.file.meta.threadId}`
            !fs.existsSync(mDir) && fs.mkdirSync(mDir)

            mDir = event.file.meta.fileType.includes('image') ?
                `${mDir}/image/${mName}` : `${mDir}/docs/${mName}`

            // Move File.
            fs.move(event.file.pathName, mDir, err => {
                if (err) return console.log(err)
            })

            // Request to yii backend db server.
            axios.post(`${BK_HTTP_URL}/api/thread-message`, { 
                thread_id: event.file.meta.threadId,
                member_id: event.file.meta.memberId,
                text: null,
                file: mDir,
                file_name: fName,
                file_type: event.file.meta.fileType.includes('image') ? 'image' : 'docs',
                created_at: event.file.meta.createdAt
            })
            .then(async _ => {
                if(event.file.meta.fileType.includes('image')) {
                    // Generate thumbnail
                    const mThumb = mDir.replace(/\/[^\/]*$/, '/thumb')
                    !fs.existsSync(mThumb) && fs.mkdirSync(mThumb)

                    await thumb({
                        source: mDir,
                        destination: mThumb,
                        quiet: true,
                        width: 250,
                        suffix: '-thumb',
                    }).catch(err => console.log(err.response))

                    // Emit back to client : IMAGE
                    const mFilePath = `${mThumb.replace(/(\.\.\/\w*\/\w*)/i, FR_HTTP_URL)}/${path.parse(mName).name}-thumb${path.parse(mName).ext}`
                    io.of('/simple').in(event.file.meta.threadId).emit('file', {
                        member_id: event.file.meta.memberId, 
                        filename: fName,
                        filepath: mFilePath,
                        type: 'image',
                        created_at: event.file.meta.createdAt,
                    })
                } else {
                    // Emit back to client = DOCS
                    io.of('/simple').in(event.file.meta.threadId).emit('file', {
                        member_id: event.file.meta.memberId, 
                        filename: fName,
                        filepath: `${mDir.replace(/(\.\.\/\w*\/\w*)/i, FR_HTTP_URL)}`,
                        type: 'docs',
                        created_at: event.file.meta.createdAt,
                    })
                }
            })
            .catch(err => console.log(err.response))
        })

        sUploader.on('error', data => {
            console.log(`Error: ${data.memo}`)
            console.log(data.error)
        })

        // Join Room Handler
        simple.on('join-room', room => {
            simple.join(room.id)
            console.log(`${name} has joined PM: ${room.id}`)
        })

        // Chat handler
        simple.on('chat', ({ cId, uId, type, message, timestamp }) => {

            axios.post(`${BK_HTTP_URL}/api/thread-message`, {
                thread_id: cId,
                member_id: uId,
                type,
                text: message,
                file: null,
                file_name: null,
                file_type: null,
                created_at: timestamp
            }).then(mMsg => {

                const payload = JSON.stringify({
                    uId,
                    message,
                    timestamp: moment(timestamp).format('MMM D, YYYY h:mm a')
                })

                io.of('/simple').in(cId).emit('chat', payload)

                // @TODO: Send to notif to browser.
                // webPush.sendNotification('', payload).catch(err => console.log(err))
            }).catch(err => console.error(err))
        })

        // Disconnect Handler
        simple.on('disconnect', _ => {
            console.log(`${name} disconnected to PM`)
        })
    })

    /////------- Group Messaging -------/////

    io.of('/group')
        .use(sharedsession(session, { autoSave: true }))
        .on('connection', group => {

        // User id & name.
        const { id, name } = group.handshake.query
        console.log(`${name} has connected to GM.`)

        // File Upload listener
        const gUploader = new siofu()
        gUploader.dir = '../frontend/web/files';
        gUploader.listen(group)

        gUploader.on('start', event => {
            if (/\.exe$/.test(event.file.name)) {
                console.log(`Aborting: ${event.file.id}`)
                gUploader.abort(event.file.id, group)
            }
        })

        gUploader.on('saved', event => {
            // Filename.
            let fName = path.basename(event.file.pathName)
            let mName = `${uuid() + path.parse(fName).ext}`

            // Create Directory.
            let mDir = `../frontend/web/files/${event.file.meta.threadId}`
            !fs.existsSync(mDir) && fs.mkdirSync(mDir)

            mDir = event.file.meta.fileType.includes('image') ?
                `${mDir}/image/${mName}` : `${mDir}/docs/${mName}`

            // Move File.
            fs.move(event.file.pathName, mDir, err => {
                if (err) return console.log(err)
            })

            // Request to yii backend db server.
            axios.post(`${BK_HTTP_URL}/api/thread-message`, { 
                thread_id: event.file.meta.threadId,
                member_id: event.file.meta.memberId,
                text: null,
                file: mDir,
                file_name: fName,
                file_type: event.file.meta.fileType.includes('image') ? 'image' : 'docs',
                created_at: event.file.meta.createdAt
            })
            .then(async _ => {
                if(event.file.meta.fileType.includes('image')) {
                    // Generate thumbnail
                    const mThumb = mDir.replace(/\/[^\/]*$/, '/thumb')
                    !fs.existsSync(mThumb) && fs.mkdirSync(mThumb)

                    await thumb({
                        source: mDir,
                        destination: mThumb,
                        quiet: true,
                        width: 250,
                        suffix: '-thumb',
                    }).catch(err => console.log(err.response))

                    // Emit back to client : IMAGE
                    const mFilePath = `${mThumb.replace(/(\.\.\/\w*\/\w*)/i, FR_HTTP_URL)}/${path.parse(mName).name}-thumb${path.parse(mName).ext}`
                    io.of('/group').in(event.file.meta.threadId).emit('file', {
                        member_id: event.file.meta.memberId, 
                        filename: fName,
                        filepath: mFilePath,
                        type: 'image',
                        created_at: event.file.meta.createdAt,
                    })
                } else {
                    // Emit back to client = DOCS
                    io.of('/group').in(event.file.meta.threadId).emit('file', {
                        member_id: event.file.meta.memberId, 
                        filename: fName,
                        filepath: `${mDir.replace(/(\.\.\/\w*\/\w*)/i, FR_HTTP_URL)}`,
                        type: 'docs',
                        created_at: event.file.meta.createdAt,
                    })
                }
            })
            .catch(err => console.log(err.response))
        })

        gUploader.on('error', data => {
            console.log(`Error: ${data.memo}`)
            console.log(data.error)
        })
        
        // Join Room Handler
        group.on('join-room', room => {
            group.join(room.id)
            console.log(`${name} has joined GM: ${room.id}`)
        })

        // Chat handler
        group.on('chat', ({ cId, uId, type, message, timestamp }) => {

            axios.post(`${BK_HTTP_URL}/api/thread-message`, {
                thread_id: cId,
                member_id: uId,
                type,
                text: message,
                file: null,
                file_name: null,
                file_type: null,
                created_at: timestamp
            }).then(mMsg => {

                const payload = JSON.stringify({
                    uId,
                    message,
                    timestamp: moment(timestamp).format('MMM D, YYYY h:mm a')
                })

                io.of('/group').in(cId).emit('chat', payload)

                // @TODO: Send to notif to browser.
                // webPush.sendNotification('', payload).catch(err => console.log(err))
            }).catch(err => console.error(err))
        })

        // Disconnect Handler
        group.on('disconnect', _ => {
            console.log(`${name} disconnected to GM`)
        })
    })
}