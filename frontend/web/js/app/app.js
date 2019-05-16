const SOCKET_URL = `http://localhost:1337`

let btnHeaderSetting,
    btnHeaderMessage,
    sidebarList,
    mainHeader,
    contentChatboxHeader,
    contentChatboxList,
    contentChatboxInput,
    contentChatboxInputBox,
    btnChatboxPhoto,
    btnChatboxFile,
    btnChatboxEmoji,
    btnChatboxSend;

let id, name, SIMPLE, GROUP
const fileTypes = [
    // Office
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'application/msword',
    'application/vnd.ms-excel',
    'application/vnd.ms-powerpoint',
    'application/vnd.sun.xml.writer',
    'application/vnd.sun.xml.writer.global',
    'application/vnd.sun.xml.calc',
    'application/vnd.sun.xml.impress',
    'application/pdf',

    // Videos
    // 'video/x-flv',
    // 'video/mp4',
    // 'application/x-mpegURL',
    // 'video/MP2T',
    // 'video/3gpp',
    // 'video/quicktime',
    // 'video/x-msvideo',
    // 'video/x-ms-wmv',

    // Others
    'text/plain'
];

const initUpload = (e, type) => {
    const mFilePond = `filepond-${type.toLowerCase()}`

    swal({
        title: `Upload your ${type === 'IMG' ? 'image/s' : 'file/s'} here ..`,
        html: `<input id="${mFilePond}" type="file" class="filepond" name="${mFilePond}" multiple data-max-files="10"/>`,
        showCloseButton: true,
        showCancelButton: false,
        showConfirmButton: true,
        focusConfirm: false,
        allowOutsideClick: false,
        customClass: 'mSwal',
        confirmButtonText: 'Upload all <i class="fa fa-upload"></i>',
        showLoaderOnConfirm: true,
        allowOutsideClick: _ => !Swal.isLoading(),
        preConfirm: async _ => {
            if(filepond.getFiles().length) {
                swal.resetValidationMessage()

                return filepond.processFiles()
            }

            return swal.showValidationMessage(`Should have enough files first.`)
        }
    }).then(res => {
        // @TODO: Handle this.
        // console.log(res)
    })

    // Hoisted.
    const filepond = FilePond.create(document.querySelector(`#${mFilePond}`), {
        acceptedFileTypes: type === 'IMG' ? ['image/*'] : fileTypes,
        instantUpload: false,
        server: {
            url: null,
            process: (fieldName, file, metadata, load, error, progress, abort) => {
                
                load('OK')

                switch(e.getAttribute('data-conn')) {
                    case 'SIMPLE':
                        const sSiofu = new SocketIOFileUpload(SIMPLE)
                        sSiofu.addEventListener('start', function(evt){
                            evt.file.meta = { 
                                threadId: mConn.cId,
                                memberId: id,
                                fileType: file.type,
                                createdAt: moment().format('YYYY-MM-DD HH:mm:ss')
                            }
                        })

                        sSiofu.submitFiles([new File([file], file.name)])
                    break
                    case 'GROUP':
                        const gSiofu = new SocketIOFileUpload(GROUP)
                        gSiofu.addEventListener('start', function(evt){
                            evt.file.meta = { 
                                threadId: mConn.cId,
                                memberId: id,
                                fileType: file.type,
                                createdAt: moment().format('YYYY-MM-DD HH:mm:ss')
                            }
                        })

                        gSiofu.submitFiles([new File([file], file.name)])
                    break
                }

                return {
                    abort: () => {
                        request.abort()
                        abort()
                    }
                }
            }
        }
    })
}

const buildURLQuery = obj => Object.entries(obj)
    .map(pair => pair.map(encodeURIComponent).join('='))
    .join('&')

const initUI = el => {
    const cImg = el.firstElementChild.children[0].src
    contentChatboxHeaderImg.setAttribute('src', cImg)

    const cName = el.firstElementChild.children[1].children[0].textContent
    mainHeaderDetailsH4.textContent = cName
    contentChatboxHeaderDetailsH4.textContent = cName

    contentChatboxInput.children[0].style.visibility = 'visible'
    contentChatboxInput.children[1].style.visibility = 'visible'
}

// d49a82aa-a674-454c-8398-2d643403e097 : John Doe
// 10c020b3-6d57-4535-bbf9-cea3b94199ea : Maria Powell

// b7eb64d0-f568-4e4a-a253-be3ded0d3b1a : John Doe
// 6786e939-9afd-4da1-a641-d8281368f5c5 : Maria Powell

const initConn = (id, name) => {
    const query = buildURLQuery({ id, name })

    SIMPLE = io(`${SOCKET_URL}/simple`, { query })
    SIMPLE.on('connect', _ => {
        console.log(`You connected to Private Messaging`)
    })

    SIMPLE.on('chat', data => {
        const { uId, message, timestamp } = data

        const src = contentChatboxHeaderImg.getAttribute('src')

        const template  = `
            <div class="msgr-main-content-chatbox-list-item">
                <span>${timestamp}</span>

                <div class="msgr-main-content-chatbox-list-item-details ${uId === id ? 'owner' : ''}">
                    <img class="img-circle" src="${src}" alt="User image">
                    <div class="msgr-main-content-chatbox-list-item-details-content">
                        <p>${message.trim()}</p>
                    </div>
                </div>
            </div>
        `
        contentChatboxList.insertAdjacentHTML('beforeend', template)
        contentChatboxList.parentNode.scrollTop = contentChatboxList.parentNode.scrollHeight
    })

    SIMPLE.on('file', data => {
        const { member_id, filename, filepath, type, created_at } = data

        const src = contentChatboxHeaderImg.getAttribute('src')

        const template = `
            <div class="msgr-main-content-chatbox-list-item">
                <span>${created_at}</span>

                <div class="msgr-main-content-chatbox-list-item-details ${member_id === id ? 'owner' : ''}">
                    <img class="img-circle" src="${src}" alt="User image">
                    <div class="msgr-main-content-chatbox-list-item-details-content">
                        ${type === 'image' ? `<img src="${filepath}" alt="${filename}" style="border: 1.5rem solid #09f; border-radius: 2.5rem; max-width:70%;">` : `<p><a href="${filepath}" target="_blank" style="color:#fff !important; text-decoration:underline;">${filename}</a></p>`}
                    </div>
                </div>
            </div>
        `

        contentChatboxList.insertAdjacentHTML('beforeend', template)
        contentChatboxList.parentNode.scrollTop = contentChatboxList.parentNode.scrollHeight
    })

    SIMPLE.on('disconnect', _ => {
        console.log(`Disconnected to PM`)
        SIMPLE.disconnect()
    })

    GROUP = io(`${SOCKET_URL}/group`, { query })
    GROUP.on('connect', _ => {
        console.log(`You connected to Group Messaging`)
    })

    GROUP.on('chat', data => {
        const { uId, message, timestamp } = data

        const template  = `
            <div class="msgr-main-content-chatbox-list-item">
                <span>${timestamp}</span>

                <div class="msgr-main-content-chatbox-list-item-details ${uId === id ? 'owner' : ''}">
                    <img class="img-circle" src="/img/1.png" alt="User image">
                    <div class="msgr-main-content-chatbox-list-item-details-content">
                        <p>${message.trim()}</p>
                    </div>
                </div>
            </div>
        `
        contentChatboxList.insertAdjacentHTML('beforeend', template)
        contentChatboxList.parentNode.scrollTop = contentChatboxList.parentNode.scrollHeight
    })

    GROUP.on('disconnect', _ => {
        console.log(`Disconnected to GM`)
        GROUP.disconnect()
    })
}

const renderUI = async (cId) => {
    if (contentChatboxList.children.length == 1) {

        const mMsg = await axios.get(`${BK_URL}/api/thread/${cId}?expand=messages`)
        mMsg.data.messages.map(msg => {
            
            let template
            const src = contentChatboxHeaderImg.getAttribute('src')

            if(msg.text) {
                // Render text
                template  = `
                    <div class="msgr-main-content-chatbox-list-item">
                        <span>${msg.created_at}</span>

                        <div class="msgr-main-content-chatbox-list-item-details ${msg.member_id === id ? 'owner' : ''}">
                            <img class="img-circle" src="${src}" alt="User image">
                            <div class="msgr-main-content-chatbox-list-item-details-content">
                                <p>${msg.text}</p>
                            </div>
                        </div>
                    </div>
                `
            } else {
                // Photo or docs
                template = `
                    <div class="msgr-main-content-chatbox-list-item">
                        <span>${msg.created_at}</span>

                        <div class="msgr-main-content-chatbox-list-item-details ${msg.member_id === id ? 'owner' : ''}">
                            <img class="img-circle" src="${src}" alt="User image">
                            <div class="msgr-main-content-chatbox-list-item-details-content">
                                ${msg.file_type === 'image' ? `<img src="${msg.file_thumb}" alt="${msg.file_name}" style="border: 1.5rem solid #09f; border-radius: 2.5rem; max-width:70%;">` : `<p><a href="${msg.file_path}" target="_blank" style="color:#fff !important; text-decoration:underline;">${msg.file_name}</a></p>`}
                            </div>
                        </div>
                    </div>
                `
            }

            contentChatboxList.insertAdjacentHTML('beforeend', template)
            contentChatboxList.parentNode.scrollTop = contentChatboxList.parentNode.scrollHeight

        })

        btnChatboxPhoto.setAttribute('data-conn', 'SIMPLE')
        btnChatboxFile.setAttribute('data-conn', 'SIMPLE')

        const tabImage = $('#tab-image')
        tabImage.nanogallery2('destroy')

        const mImages = await axios.get(`${BK_URL}/api/thread/${cId}?expand=images`)
        tabImage.nanogallery2({
            items: mImages.data.images.map(msg => {
                if(msg.file_path) {
                    return {
                        src: msg.file_path,
                        srct: msg.file_thumb,
                        title: msg.file_name
                    }
                }
            }),
            thumbnailWidth: 'auto',
            thumbnailHeight: 100,
        })

        const tabDocs = $('#tab-docs')
        tabDocs.html('')

        const mDocs = await axios.get(`${BK_URL}/api/thread/${cId}?expand=docs`)
        mDocs.data.docs.map(doc => {
            tabDocs.append(`
                <li style="margin: 1rem 0;">
                    <a href="${doc.file_path}" target="_blank" style="text-decoration:underline;" title="${doc.file_name}">${doc.file_name}</a> <br/>
                    <span class="label label-default">${moment(doc.created_at).format('MMM. DD, YYYY hh:mm a')}</span>
                </li>
            `)
        })
    }
}

let mConn = {};
const connect = async (el, cId, type) => {

    switch(type) {
        case 'SIMPLE':
            if(mConn.cId === undefined) {
                SIMPLE.emit('join-room', { id: cId })
                mConn = { cId, type: 'SIMPLE' }
            }

            if(cId !== mConn.cId) {
                // Reset connection
                SIMPLE.emit('join-room', { id: cId })
                mConn = { cId, type: 'SIMPLE' }

                // Clear the message
                $('#spinner-container').removeClass('spinner-show').addClass('spinner-hide')
                for (const mMsg of $(contentChatboxList).children().splice(1, $(contentChatboxList).children().length)) {
                    $(mMsg).remove()
                }

                renderUI(mConn.cId)
            } else {

                renderUI(mConn.cId)
            }
        break;
        case 'GROUP':
            if(mConn.cId === undefined) {
                GROUP.emit('join-room', { id: cId })
                mConn = { cId, type: 'GROUP' }
            }

            if(cId !== mConn.cId) {
                // Reset connection
                GROUP.emit('join-room', { id: cId })
                mConn = { cId, type: 'GROUP' }

                // Clear the message
                $('#spinner-container').removeClass('spinner-show').addClass('spinner-hide')
                for (const mMsg of $(contentChatboxList).children().splice(1, $(contentChatboxList).children().length)) {
                    $(mMsg).remove()
                }

                renderUI(mConn.cId)
            } else {

                renderUI(mConn.cId)
            }
        break;
    }

    initUI(el)
}

const groupConfirm = params => {

    const h4 = params.parentElement
        .previousElementSibling.children[1].children[0]

    const mId = h4.dataset.id
    const mName = h4.textContent
    const mMembers = [
        { member_id: id, role: 'ADMIN' },
        { member_id: mId, role: 'MEMBER' }
    ]

    swal({
        width: '50rem',
        type: 'question',
        title: 'Please choose an option',
        focusConfirm: false,
        showCloseButton: true,
        showCancelButton: true,
        showConfirmButton: true,
        allowOutsideClick: false,
        confirmButtonColor: '#5cb85c',
        confirmButtonText: `
            <i class="fa fa-user-plus fa-2x" aria-hidden="true"></i>
            <br/>Add <strong>${mName}</strong> <br/>to a New Group
        `,
        confirmButtonAriaLabel: 'Add to New Group',
        cancelButtonColor: '#337ab7',
        cancelButtonText: `
            <i class="fa fa-users fa-2x" aria-hidden="true"></i>
            <br/>Add <strong>${mName}</strong> <br/>to an Existing Group
        `,
        cancelButtonAriaLabel: 'Add to an Existing Group',
    }).then(res => {
        if(res.value) {
            // New Group
            swal({
                title: 'Please specify a group name',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                confirmButtonText: 'Submit',
                showLoaderOnConfirm: true,
                preConfirm: grpName => {
                    if(grpName) {
                        return fetch(`${BK_URL}/api/thread`, {
                            method: 'POST',
                            body: JSON.stringify({ 
                                type: 'GROUP',
                                name: grpName,
                                members: mMembers
                            }),
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        }).then(async resp => {
                            if (!resp.ok) {
                                throw new Error(resp.statusText)
                            }

                            // Crappy, Recreating response on frontend.
                            const result = await resp.json()
                            result.result = grpName
                            result.members = mMembers

                            return result
                        }).catch(err => {
                            Swal.showValidationMessage(
                                `Request failed: ${err}`
                            )
                        })
                    } else {
                        Swal.showValidationMessage(`Please specify a group name`)
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then(res => {
                console.log(res)
            })
        } else {
            // Existing Group
            console.log('Existing Group')
        }
    })
}

document.addEventListener('DOMContentLoaded', async _ => {

    FilePond.registerPlugin(
        FilePondPluginImageCrop,
        FilePondPluginImageEdit,
	    FilePondPluginImageExifOrientation,
        FilePondPluginImagePreview,
        FilePondPluginImageResize,
        FilePondPluginImageTransform,
        FilePondPluginImageValidateSize,

        FilePondPluginFileEncode,
        FilePondPluginFileMetadata,
        FilePondPluginFilePoster,
        FilePondPluginFileRename,
	    FilePondPluginFileValidateSize,
	    FilePondPluginFileValidateType,
    )
    
    btnHeaderSetting = document.querySelector('#btn-header-setting')
    btnHeaderMessage = document.querySelector('#btn-header-message')
    sidebarList = document.querySelector('.msgr-sidebar-list')
    mainHeaderDetailsH4 = document.querySelector('.msgr-main-header-details > h4')
    contentChatboxHeaderImg = document.querySelector('.msgr-main-content-chatbox-header > img')
    contentChatboxHeaderDetailsH4 = document.querySelector('.msgr-main-content-chatbox-header-details > h4')
    contentChatboxList = document.querySelector('.msgr-main-content-chatbox-list > .os-padding > .os-viewport > .os-content')
    contentChatboxInput = document.querySelector('.msgr-main-content-chatbox-input')
    contentChatboxInputBox = document.querySelector('#content-chatbox-input-box')
    btnChatboxPhoto = document.querySelector('#btn-chatbox-photo')
    btnChatboxFile = document.querySelector('#btn-chatbox-file')
    btnChatboxEmoji = document.querySelector('#btn-chatbox-emoji')
    btnChatboxSend = document.querySelector('#btn-chatbox-send')

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
    ]).then(async result => {

        const x = result.value.filter(x => x)
        if (!(x === undefined || x.length == 0)) {

            // id = result.value[0]
            // name = result.value[1]

            id = 'f9c159af-6f58-441d-b26f-a6ab4b497eaf'
            name = 'Maria Powell'

            // 312615cc-96f1-4e0f-9da5-ef482e72d889

            axios.get(`${BK_URL}/api/member/${id}?expand=threads`).then(resp => {
                const template = resp.data.threads.map(th => {
                    const mBtn = `
                        <button type="button" id="btn-list-item-setting" class="btn btn-default btn-sm">
                            <i class="fa fa-cog fa-fw"></i>
                        </button>
                    `

                    return `
                        <div class="msgr-sidebar-list-item" onClick="connect(this, '${th.id}', '${th.type}')">
                            <div class="msgr-sidebar-list-item-content">
                                <img class="img-circle" src="/img/${th.type == 'GROUP' ? '3' : th.sex == 'M' ? '1' : '2'}.png" alt="User image">                        
                                <div class="msgr-sidebar-list-item-content-details">
                                    <h4>${th.name}</h4>
                                    <p>${th.message ? th.message.latest : '-'}</p>
                                </div>
                            </div>
            
                            <div class="msgr-sidebar-list-item-settings">
                                <span>${th.message ? moment(th.message.time).format('ddd') : '-'}</span>
                            </div>
                        </div>
                    `
                }).join('')
        
                sidebarList.innerHTML = template
        
                OverlayScrollbars(sidebarList, {})
            })

            initConn(id, name)

            contentChatboxInputBox.addEventListener('keydown', e => {
                if(e.keyCode === 13 && !e.shiftKey) {
                    e.preventDefault()
            
                    const timestamp = moment().format('YYYY-MM-DD HH:mm:ss')
                    const message = e.target.value

                    if(mConn.type == 'GROUP') {
                        GROUP.emit('chat', { cId: mConn.cId, uId: id, message, timestamp })
                    }

                    SIMPLE.emit('chat', { cId: mConn.cId, uId: id, message, timestamp })

                    contentChatboxInputBox.value = ''
                }
            })
        }
    })
})