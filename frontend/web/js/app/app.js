// UI references

// Sidebar Header
const btnHeaderSetting = $('#btn-header-setting')
const btnHeaderMessage = $('#btn-header-message')

// Sidebar List Item : Must have built-in "onClick" on view
const sidebarListItem = $('#sidebar-list-item')
const btnListItemSetting = $('#btn-list-item-setting')

// Content Chatbox Input
const contentChatboxInputBox = $('#content-chatbox-input-box')
const btnChatboxPhoto = $('#btn-chatbox-photo')
const btnChatboxFile = $('#btn-chatbox-file')
const btnChatboxEmoji = $('#btn-chatbox-emoji')
const btnChatboxSend = $('#btn-chatbox-send')

// Content Tools User : Must have built-in "onClick" on view
const btnUserChat = $('#btn-user-chat')
const btnUserGroup = $('#btn-user-group')

// Initialize SocketIO
const socket = io('http://localhost:1337/', { query: `_id=${1}&name=${'John Doe'}` })

socket.on('connect', _ => {
    console.log(`Connected to server.`)
})