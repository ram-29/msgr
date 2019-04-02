const cors = require('cors')
const morgan = require('morgan')
const express = require('express')
const bodyParser = require('body-parser')
const session = require('express-session')
const errorhandler = require('errorhandler')
const app = express()

app.use(cors())
app.use(errorhandler())
app.use(bodyParser.json())
app.use(morgan('combined'))
app.use(bodyParser.urlencoded({ extended: false }))
app.use(session({ secret: '$2a$07$sXmjRkhWZxKDsudVk281X.Y.RLqgzlfysiBOfXizwLLzea7IbUGWG', cookie: { maxAge: 60000 }, resave: false, saveUninitialized: false }))

const server = require('http').Server(app)
const io = require('socket.io')(server)

const PORT = process.env.PORT || 1337

server.listen(PORT, _ => { console.log(`Server running on port ${PORT}.`) })

io.on('connection', client => {

    // User _id & name.
    const { _id, name } = client.handshake.query
    console.log(`${name} has been connected.`)

    // User disconnect.
    client.on('disconnect', _ => { console.log(`${name} has been disconnected.`) })
    
})