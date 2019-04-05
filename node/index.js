const cors = require('cors')
const axios = require('axios')
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

// Change `BASE_URL` on production.
const BASE_URL = `http://bk.msgr.io`
const PORT = process.env.PORT || 1337

server.listen(PORT, _ => { console.log(`Server running on port ${PORT}.`) })

axios
    .get(`${BASE_URL}/api/thread`)
    .then(resp => {
        resp.data.map(thread => {
            io.of(`/${thread.id}`).on('connection', client => {

                // User id & name.
                const { id, name } = client.handshake.query
                console.log(`${name} connected to ${client.nsp.name}`)

                // Emit back to client
                client.on('chat', data => client.broadcast.emit('chat', data))
                
                // Update user login status.
                // axios.patch(`${BASE_URL}/api/member/${id}`, { type: 'CONNECT' })
            
                // Request to yii backend server.
                // axios
                //     .get(`${BASE_URL}/api/member/${id}?expand=threads`)
                //     .then(resp => client.emit('member-data', resp.data))
            
                // User disconnect.
                client.on('disconnect', _ => {
            
                    // Update user login status.
                    console.log(`${name} disconnected to ${client.nsp.name}`)
                    // axios.patch(`${BASE_URL}/api/member/${id}`, { type: 'DISCONNECT' })
                })
            })
        })
    })