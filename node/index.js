const cors = require('cors')
const morgan = require('morgan')
const express = require('express')
const bodyParser = require('body-parser')
const errorhandler = require('errorhandler')
const siofu = require("socketio-file-upload")
const sharedsession = require('express-socket.io-session')

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
const BASE_URL = `http://bk.msgr.io`
const PORT = process.env.PORT || 1337

server.listen(PORT, _ => { console.log(`Server running on port ${PORT}.`) })

// Private Messaging
io.of('/simple')
    .use(sharedsession(session, { autoSave: true }))
    .on('connection', simple => {

    // User id & name.
    const { id, name } = simple.handshake.query

    // Upload listener
    const sUploader = new siofu()
    sUploader.dir = '../frontend/web/img';
    sUploader.listen(simple)

    sUploader.on('start', event => {
		if (/\.exe$/.test(event.file.name)) {
			console.log(`Aborting: ${event.file.id}`)
			sUploader.abort(event.file.id, socket)
		}
	})

    sUploader.on('saved', event => {
		console.log(event.file)
		event.file.clientDetail.base = event.file.base
	})

    sUploader.on('error', data => {
		console.log(`Error: ${data.memo}`)
		console.log(data.error)
    })
    
    // Upload Image Handler
    simple.on('upload-img', data => {
        console.log(data)
    })

    // Join Room Handler
    simple.on('join-room', room => {
        simple.join(room.id)
        console.log(`${name} has joined PM: ${room.id}`)
    })

    // Chat handler
    simple.on('chat', ({ cId, uId, message, timestamp }) => {
        io.of('/simple').in(cId).emit('chat', { uId, message, timestamp })
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

    // Upload listener
    const gUploader = new siofu()
    gUploader.listen(group)

    gUploader.on('start', event => {
		if (/\.exe$/.test(event.file.name)) {
			console.log(`Aborting: ${event.file.id}`)
			sUploader.abort(event.file.id, socket)
		}
	})

    gUploader.on('saved', event => {
		console.log(event.file)
		event.file.clientDetail.base = event.file.base
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
        console.log(`${name} has joined PM: ${room.id}`)
    })

    // Chat handler
    group.on('chat', ({ cId, uId, message, timestamp }) => {
        io.of('/group').in(cId).emit('chat', { uId, message, timestamp })
    })

    // Disconnect Handler
    group.on('disconnect', _ => {
        console.log(`${name} disconnected to GM`)
    })
})