// UI references

// Sidebar Header
const btnHeaderSetting = $('#btn-header-setting')
const btnHeaderMessage = $('#btn-header-message')

// Sidebar List Item : Must have built-in "onClick" on view
const sidebarListItem = $('#sidebar-list-item')
const btnListItemSetting = $('#btn-list-item-setting')

// Content Chatbox Input
const contentChatboxList = $('.msgr-main-content-chatbox-list > .os-padding > .os-viewport > .os-content')
const contentChatboxInputBox = $('#content-chatbox-input-box')
const btnChatboxPhoto = $('#btn-chatbox-photo')
const btnChatboxFile = $('#btn-chatbox-file')
const btnChatboxEmoji = $('#btn-chatbox-emoji')
const btnChatboxSend = $('#btn-chatbox-send')

// Content Tools User : Must have built-in "onClick" on view
const btnUserChat = $('#btn-user-chat')
const btnUserGroup = $('#btn-user-group')

// Change `BASE_URL` on production.
const BASE_URL = `http://bk.msgr.io`

let socket = null
const connect = (el, tId, id, name) => {

    // Generate query params
    const query = $.param({ id, name })

    // Initialize SocketIO
    socket = io(`http://localhost:1337/${tId}`, { query, autoConnect: false })

    console.log(socket.id)

    // Connection events
    socket.on('connect', _ => console.log(`You connected to ${tId}`))
    socket.on('disconnect', _ => console.log(`You disconnected to ${tId}`))
    socket.on('chat', data => {

        const {message, timestamp} = data

        const template  = `
            <div class="msgr-main-content-chatbox-list-item">
                <span>${timestamp}</span>

                <div class="msgr-main-content-chatbox-list-item-details">
                    <img class="img-circle" src="/img/1.png" alt="User image">
                    <div class="msgr-main-content-chatbox-list-item-details-content">
                        <p>${message}</p>
                    </div>
                </div>
            </div>
        `
        contentChatboxList.append(template)
        contentChatboxList.scrollTop(contentChatboxList.scrollHeight)
    })

    // Set chat image
    const cImg = el.firstElementChild.children[0].src
    $('.msgr-main-content-chatbox-header > img').attr('src', cImg)

    // Set chat name
    const cName = el.firstElementChild.children[1].children[0].textContent
    $('.msgr-main-header-details > h4').text(cName)
    $('.msgr-main-content-chatbox-header-details > h4').text(cName)
}

contentChatboxInputBox.on('keydown', e => {
    if(e.keyCode === 13 && !e.shiftKey) {
        e.preventDefault()

        if(socket) {
            // Render to self.
            const timestamp = moment().format('MMM DD, YYYY, hh:mm a')
            const message = e.target.value

            const template  = `
                <div class="msgr-main-content-chatbox-list-item">
                    <span>${timestamp}</span>

                    <div class="msgr-main-content-chatbox-list-item-details owner">
                        <img class="img-circle" src="/img/1.png" alt="User image">
                        <div class="msgr-main-content-chatbox-list-item-details-content">
                            <p>${message}</p>
                        </div>
                    </div>
                </div>
            `
            contentChatboxList.append(template)

            // Emit to server.
            socket.emit('chat', { message, timestamp })
        }        
    }
})

// const id = 'd49a82aa-a674-454c-8398-2d643403e097'
// const name = 'John Doe'

const initCon = (id, name) => {
    axios.get(`${BASE_URL}/api/member/${id}?expand=threads`).then(resp => {

        // Generate sidebar list.
        const template = resp.data.threads.map(th => {
            return `
                <div class="msgr-sidebar-list-item" onClick="connect(this, '${th.id}', '${id}', '${name}')">
                    <div class="msgr-sidebar-list-item-content">
                        <img class="img-circle" src="/img/${th.type == 'GROUP' ? '3' : '1'}.png" alt="User image">                        
                        <div class="msgr-sidebar-list-item-content-details">
                            <h4>${th.name}</h4>
                            <p>${th.message ? th.message.latest : '-'}</p>
                        </div>
                    </div>
    
                    <div class="msgr-sidebar-list-item-settings">
                        <span>${th.message ? moment(th.message.time).format('ddd') : '-'}</span>
                        <button type="button" id="btn-list-item-setting" class="btn btn-default btn-sm">
                            <i class="fa fa-cog fa-fw"></i>
                        </button>
                    </div>
                </div>
            `
        }).join('')

        // Inject sidebar list.
        $('.msgr-sidebar-list').html(template)

        // Initialize sidebar.
        $('.msgr-sidebar-list').overlayScrollbars({})
    })
}

// initCon()

// Sample Users
// d49a82aa-a674-454c-8398-2d643403e097 : John Doe
// 10c020b3-6d57-4535-bbf9-cea3b94199ea : Maria Powell

// b7eb64d0-f568-4e4a-a253-be3ded0d3b1a : John Doe
// 6786e939-9afd-4da1-a641-d8281368f5c5 : Maria Powell

swal.mixin({
    input: 'text',
    confirmButtonText: 'Next &rarr;',
    progressSteps: ['1', '2'],
    showCloseButton: false,
    allowOutsideClick: false,
    showCancelButton: false,
}).queue([
    'Enter ID',
    'Enter Name',
]).then(result => {
    const x = result.value.filter(x => x)
    if (!(x === undefined || x.length == 0)) {
        const id = result.value[0]
        const name = result.value[1]
        initCon(id, name)
    }
})