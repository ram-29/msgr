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

// For testing purpose
const id = 'd49a82aa-a674-454c-8398-2d643403e097'
const name = 'John Doe'

// Generate query params
const query = $.param({ id, name })

// Initialize SocketIO
const socket = io(`http://localhost:1337/${id}`, { query })

socket.on('connect', _ => console.log(`Connected to server.`))

socket.on('member-data', resp => {
    // Generate sidebar list.
    const template = resp.threads.map(th => {
        return `
            <div class="msgr-sidebar-list-item">
                <div class="msgr-sidebar-list-item-content">
                    <img class="img-circle" src="/img/${th.type == 'GROUP' ? '3' : '1'}.png" alt="User image">                        
                    <div class="msgr-sidebar-list-item-content-details">
                        <h4>${th.name}</h4>
                        <p>${th.message ? th.message.latest : '-'}</p>
                    </div>
                </div>

                <div class="msgr-sidebar-list-item-settings">
                    <span>${th.message ? moment(th.message.time).format('ddd') : '-'}</span>
                    <button type="button" id="btn-list-item-setting" class="btn btn-default btn-sm"><i class="fa fa-cog fa-fw"></i></button>
                </div>
            </div>
        `
    }).join('')

    // Inject sidebar list.
    $('.msgr-sidebar-list').html(template)

    // Initialize sidebar.
    $('.msgr-sidebar-list').overlayScrollbars({})
})