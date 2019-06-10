const path = require('path')
const cors = require('cors')
const fs = require('fs-extra')
const axios = require('axios')
const uuid = require('uuid/v4')
const morgan = require('morgan')
const moment = require('moment')
const express = require('express')
const webPush = require('web-push')
const bodyParser = require('body-parser')
const errorhandler = require('errorhandler')
const siofu = require('socketio-file-upload')
const thumb = require('node-thumbnail').thumb
const sharedsession = require('express-socket.io-session')

const pubVapidKey = 'BJEwuKSfuPqPqre3WU4mebqobg51_7RGH33f3wPSrjm1VOUzIHAS5op9Ia2usNrK8hmGm_f1klXIl-JJd3cClO0'
const priVapidKey = 'UbzsbwwqAsKbUloLj3Ym5TbQLGp_qS3sE791ba0ublY'

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

const server = require('http').Server(app)
const io = require('socket.io')(server)

// Change `BASE_URL` on production.
const BK_URL = `http://localhost:80/msgr/backend/web`
const FR_URL = `http://localhost:80/msgr/frontend/web`

const PORT = process.env.PORT || 1337

server.listen(PORT, _ => { console.log(`Server running on port ${PORT}.`) })

// Private Messaging
io.of('/simple')
    .use(sharedsession(session, { autoSave: true }))
    .on('connection', simple => {

    // User id & name.
    const { id, name } = simple.handshake.query

    // File Upload listener
    const sUploader = new siofu()
    sUploader.dir = '../frontend/web/files';
    sUploader.listen(simple)

    sUploader.on('start', event => {
		if (/\.exe$/.test(event.file.name)) {
			console.log(`Aborting: ${event.file.id}`)
			sUploader.abort(event.file.id, socket)
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
        axios.post(`${BK_URL}/api/thread-message`, { 
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
                })

                // Emit back to client : IMAGE
                const mFilePath = `${mThumb.replace(/(\.\.\/\w*\/\w*)/i, FR_URL)}/${path.parse(mName).name}-thumb${path.parse(mName).ext}`
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
                    filepath: `${mDir.replace(/(\.\.\/\w*\/\w*)/i, FR_URL)}`,
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
    simple.on('chat', ({ cId, uId, message, timestamp }) => {

        axios.post(`${BK_URL}/api/thread-message`, {
            thread_id: cId,
            member_id: uId,
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
            webPush.sendNotification('', payload).catch(err => console.log(err))
        })
    })

    // Disconnect Handler
    simple.on('disconnect', _ => {
        console.log(`${name} disconnected to PM`)
    })
})

// Group Messaging
io.of('/group')
    .use(sharedsession(session, { autoSave: true }))
    .on('connection', group => {

    // User id & name.
    const { id, name } = group.handshake.query

    // File Upload listener
    const gUploader = new siofu()
    gUploader.dir = '../frontend/web/files';
    gUploader.listen(group)

    gUploader.on('start', event => {
		if (/\.exe$/.test(event.file.name)) {
			console.log(`Aborting: ${event.file.id}`)
			gUploader.abort(event.file.id, socket)
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
        axios.post(`${BK_URL}/api/thread-message`, { 
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
                })

                // Emit back to client : IMAGE
                const mFilePath = `${mThumb.replace(/(\.\.\/\w*\/\w*)/i, FR_URL)}/${path.parse(mName).name}-thumb${path.parse(mName).ext}`
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
                    filepath: `${mDir.replace(/(\.\.\/\w*\/\w*)/i, FR_URL)}`,
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
    
    // Upload Image Handler
    group.on('upload-img', data => {
        console.log(data)
    })

    // Join Room Handler
    group.on('join-room', room => {
        group.join(room.id)
        console.log(`${name} has joined GM: ${room.id}`)
    })

    // Chat handler
    group.on('chat', ({ cId, uId, message, timestamp }) => {

        axios.post(`${BK_URL}/api/thread-message`, {
            thread_id: cId,
            member_id: uId,
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
            webPush.sendNotification('', payload).catch(err => console.log(err))
        })
    })

    // Disconnect Handler
    group.on('disconnect', _ => {
        console.log(`${name} disconnected to GM`)
    })
})