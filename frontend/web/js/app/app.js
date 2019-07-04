const SOCKET_HTTP_URL = `http://localhost:1337`
const SOCKET_HTTPS_URL = `http://localhost:7331`

const FR_HTTP_URL = `http://fr.msgr.io`
const BK_HTTP_URL = `http://bk.msgr.io`

const FR_HTTPS_URL = `https://fr.msgr.io`
const BK_HTTPS_URL = `https://bk.msgr.io`

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
    btnChatboxSend,
    cMsg,
    btnDetailsHamburg,
    btnEmployeeSearch,
    inputChatSearch,
    inputEmployeeSearch,
    userList;

let SIMPLE, GROUP
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

const leaveGroup = grpId => {
    console.log(grpId)
}

const initSearch = (pElem, cName, sTerm) => {
    Array.from(pElem.getElementsByClassName(cName))
    .forEach(mItem => {
        if(mItem.children[0].children[1].children[0].textContent
            .toLowerCase().indexOf(sTerm.toLowerCase()) != -1
        ) {
            mItem.style.display = 'flex'
        } else {
            mItem.style.display = 'none'
        }
    })
}

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
                                memberId: M_ID,
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
                                memberId: M_ID,
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

    cMsg = el.firstElementChild.children[1].children[1]

    contentChatboxInput.children[0].style.visibility = 'visible'
    contentChatboxInput.children[1].style.visibility = 'visible'
}

const playSound = uId => {
    if (M_ID !== uId) {
        const sound = new Howl({ src: [`${FR_HTTP_URL}/audio/notif.mp3` ]})
        sound.play()
    }
}

const initConn = (M_ID, M_NAME) => {
    const query = buildURLQuery({ id: M_ID, name: M_NAME })

    SIMPLE = io(`${SOCKET_HTTP_URL}/simple`, { query, secure: true })

    SIMPLE.on('connect', _ => {
        console.log(`You connected to Private Messaging`)
    })

    SIMPLE.on('chat', data => {
        const { uId, message, timestamp } = JSON.parse(data)

        const src = contentChatboxHeaderImg.getAttribute('src')

        const mDate = moment(timestamp).format('MMM DD, YYYY')
        const mTime = moment(timestamp).format('hh:mm a')

        const mPrevDate = contentChatboxList.lastElementChild.firstElementChild.firstElementChild
        const mPrevTime = contentChatboxList.lastElementChild.firstElementChild.lastElementChild

        const template  = `
            <div class="msgr-main-content-chatbox-list-item">
                <span class="${(mPrevDate.textContent == mDate) && (mPrevTime.textContent == mTime) ? 'stamp-hide' : ''}">
                    <span class="${mPrevDate.textContent == mDate ? 'stamp-hide' : ''}">${mDate}</span> 
                    <span class="${mPrevTime.textContent == mTime ? 'stamp-hide' : ''}">${mTime}</span>
                </span>

                <div class="msgr-main-content-chatbox-list-item-details ${uId === M_ID ? 'owner' : ''}">
                    <img class="img-circle" src="${src}" alt="User image">
                    <div class="msgr-main-content-chatbox-list-item-details-content">
                        <p>${message.trim()}</p>
                    </div>
                </div>
            </div>
        `

        cMsg.textContent = strTruncate(message, 20)
        contentChatboxList.insertAdjacentHTML('beforeend', template)
        contentChatboxList.parentNode.scrollTop = contentChatboxList.parentNode.scrollHeight
        playSound(uId)
    })

    SIMPLE.on('file', async data => {
        const { member_id, filename, filepath, type, created_at } = data

        const src = contentChatboxHeaderImg.getAttribute('src')

        const mDate = moment(created_at).format('MMM DD, YYYY')
        const mTime = moment(created_at).format('hh:mm a')

        const mPrevDate = contentChatboxList.lastElementChild.firstElementChild.firstElementChild
        const mPrevTime = contentChatboxList.lastElementChild.firstElementChild.lastElementChild

        const template = `
            <div class="msgr-main-content-chatbox-list-item">
                <span class="${(mPrevDate.textContent == mDate) && (mPrevTime.textContent == mTime) ? 'stamp-hide' : ''}">
                    <span class="${mPrevDate.textContent == mDate ? 'stamp-hide' : ''}">${mDate}</span>
                    <span class="${mPrevTime.textContent == mTime ? 'stamp-hide' : ''}">${mTime}</span>
                </span>

                <div class="msgr-main-content-chatbox-list-item-details ${member_id === M_ID ? 'owner' : ''}">
                    <img class="img-circle" src="${src}" alt="User image">
                    <div class="msgr-main-content-chatbox-list-item-details-content">
                        ${type === 'image' ? `<img src="${filepath}" alt="${filename}" style="border: 1.5rem solid #09f; border-radius: 2.5rem; max-width:70%;">` : `<p><a href="${filepath}" target="_blank" style="color:#fff !important; text-decoration:underline;">${filename}</a></p>`}
                    </div>
                </div>
            </div>
        `

        if(type === 'image') {
            const tabImage = $('#tab-image')
            tabImage.nanogallery2('destroy')
    
            const mImages = await axios.get(`${BK_HTTP_URL}/api/thread/${mConn.cId}?expand=images`)
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

        } else {
            const tabDocs = $('#tab-docs')

            tabDocs.append(`
                <li style="margin: 1rem 0;">
                    <a href="${filepath}" target="_blank" style="text-decoration:underline;" title="${filename}">${filename}</a> <br/>
                    <span class="label label-default">${moment(created_at).format('MMM DD, YYYY hh:mm a')}</span>
                </li>
            `)
        }

        cMsg.textContent = strTruncate((type === 'image' ? 'You sent an image.' : 'You sent a document.'), 20)
        contentChatboxList.insertAdjacentHTML('beforeend', template)
        contentChatboxList.parentNode.scrollTop = contentChatboxList.parentNode.scrollHeight
        playSound(member_id)
    })

    SIMPLE.on('disconnect', _ => {
        console.log(`Disconnected to PM`)
        SIMPLE.disconnect()
    })

    /////------- GROUP -------/////

    GROUP = io(`${SOCKET_HTTP_URL}/group`, { query, secure: true })

    GROUP.on('connect', _ => {
        console.log(`You connected to Group Messaging`)
    })

    GROUP.on('chat', data => {
        const { uId, message, timestamp } = JSON.parse(data)

        const src = contentChatboxHeaderImg.getAttribute('src')

        const mDate = moment(timestamp).format('MMM DD, YYYY')
        const mTime = moment(timestamp).format('hh:mm a')

        const mPrevDate = contentChatboxList.lastElementChild.firstElementChild.firstElementChild
        const mPrevTime = contentChatboxList.lastElementChild.firstElementChild.lastElementChild

        const template  = `
            <div class="msgr-main-content-chatbox-list-item">
                <span class="${(mPrevDate.textContent == mDate) && (mPrevTime.textContent == mTime) ? 'stamp-hide' : ''}">
                    <span class="${mPrevDate.textContent == mDate ? 'stamp-hide' : ''}">${mDate}</span> 
                    <span class="${mPrevTime.textContent == mTime ? 'stamp-hide' : ''}">${mTime}</span>
                </span>

                <div class="msgr-main-content-chatbox-list-item-details ${uId === M_ID ? 'owner' : ''}">
                    <img class="img-circle" src="${src}" alt="User image">
                    <div class="msgr-main-content-chatbox-list-item-details-content">
                        <p>${message.trim()}</p>
                    </div>
                </div>
            </div>
        `

        cMsg.textContent = strTruncate(message, 20)
        contentChatboxList.insertAdjacentHTML('beforeend', template)
        contentChatboxList.parentNode.scrollTop = contentChatboxList.parentNode.scrollHeight
        playSound(uId)
    })

    GROUP.on('file', async data => {
        const { member_id, filename, filepath, type, created_at } = data

        const src = contentChatboxHeaderImg.getAttribute('src')

        const mDate = moment(created_at).format('MMM DD, YYYY')
        const mTime = moment(created_at).format('hh:mm a')

        const mPrevDate = contentChatboxList.lastElementChild.firstElementChild.firstElementChild
        const mPrevTime = contentChatboxList.lastElementChild.firstElementChild.lastElementChild

        const template = `
            <div class="msgr-main-content-chatbox-list-item">
                <span class="${(mPrevDate.textContent == mDate) && (mPrevTime.textContent == mTime) ? 'stamp-hide' : ''}">
                    <span class="${mPrevDate.textContent == mDate ? 'stamp-hide' : ''}">${mDate}</span>
                    <span class="${mPrevTime.textContent == mTime ? 'stamp-hide' : ''}">${mTime}</span>
                </span>

                <div class="msgr-main-content-chatbox-list-item-details ${member_id === M_ID ? 'owner' : ''}">
                    <img class="img-circle" src="${src}" alt="User image">
                    <div class="msgr-main-content-chatbox-list-item-details-content">
                        ${type === 'image' ? `<img src="${filepath}" alt="${filename}" style="border: 1.5rem solid #09f; border-radius: 2.5rem; max-width:70%;">` : `<p><a href="${filepath}" target="_blank" style="color:#fff !important; text-decoration:underline;">${filename}</a></p>`}
                    </div>
                </div>
            </div>
        `

        if(type === 'image') {
            const tabImage = $('#tab-image')
            tabImage.nanogallery2('destroy')
    
            const mImages = await axios.get(`${BK_HTTP_URL}/api/thread/${mConn.cId}?expand=images`)
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

        } else {
            const tabDocs = $('#tab-docs')

            tabDocs.append(`
                <li style="margin: 1rem 0;">
                    <a href="${filepath}" target="_blank" style="text-decoration:underline;" title="${filename}">${filename}</a> <br/>
                    <span class="label label-default">${moment(created_at).format('MMM DD, YYYY hh:mm a')}</span>
                </li>
            `)
        }

        cMsg.textContent = strTruncate((type === 'image' ? 'You sent an image.' : 'You sent a document.'), 20)
        contentChatboxList.insertAdjacentHTML('beforeend', template)
        contentChatboxList.parentNode.scrollTop = contentChatboxList.parentNode.scrollHeight
        playSound(member_id)
    })

    GROUP.on('disconnect', _ => {
        console.log(`Disconnected to GM`)
        GROUP.disconnect()
    })
}

const renderUI = async (cId) => {
    if (contentChatboxList.children.length == 1) {

        const tabAboutHeader = $('.tab-about-header')
        const tabAbout = $('#tab-about')
        
        tabAboutHeader.empty()
        tabAbout.empty()
        
        const req = await axios.get(`${BK_HTTP_URL}/api/thread/${cId}?expand=members`)

        if(req.data.type == 'GROUP') {
            tabAbout.append(`<h4>Members</h4>`)

            req.data.members.map(mMem => {
                tabAbout.append(`<li>${mMem.name} ${(mMem.id === M_ID) ? '(You)' : ''}</li>`)
            })

            tabAbout.append(`
                <div style="display:flex; justify-content:center; padding:1rem 0;">
                    <button class="btn btn-danger" onclick="leaveGroup('${req.data.id}')">
                        Leave group
                        <i class="fa fa-sign-out" aria-hidden="true"></i>
                    </button>
                </div>
            `)
        }

        tabAboutHeader.append(`
            <img class="img-circle" src="${contentChatboxHeaderImg.src}" alt="User image">
            <h4>${contentChatboxHeaderDetailsH4.textContent}</h4>
        `)

        const mMsg = await axios.get(`${BK_HTTP_URL}/api/thread/${cId}?expand=messages`)
        mMsg.data.messages.map(msg => {
            
            let template
            const src = contentChatboxHeaderImg.getAttribute('src')

            const mDate = moment(msg.created_at).format('MMM DD, YYYY')
            const mTime = moment(msg.created_at).format('hh:mm a')

            const mPrevDate = contentChatboxList.lastElementChild.firstElementChild.firstElementChild
            const mPrevTime = contentChatboxList.lastElementChild.firstElementChild.lastElementChild

            const { id, name, sex } = msg.member

            if(msg.text) {
                // Render text
                template  = `
                    <div class="msgr-main-content-chatbox-list-item">
                        <span class="${(mPrevDate.textContent == mDate) && (mPrevTime.textContent == mTime) ? 'stamp-hide' : ''}">
                            <span class="${mPrevDate.textContent == mDate ? 'stamp-hide' : ''}">${mDate}</span> 
                            <span class="${mPrevTime.textContent == mTime ? 'stamp-hide' : ''}">${mTime}</span>
                        </span>

                        <p style="display: ${mMsg.data.type == 'GROUP' ? (id === M_ID ? 'none;' : 'block;') : 'none;'} color:#999; margin:0 0 .5rem;">${name}</p>
                        <div class="msgr-main-content-chatbox-list-item-details ${id === M_ID ? 'owner' : ''}">
                            <img class="img-circle" src="${mMsg.data.type == 'GROUP' ? (sex == 'M' ? '/img/1.png' : '/img/2.png') : src}" alt="User image">
                            <div class="msgr-main-content-chatbox-list-item-details-content">
                                <p>${msg.text}</p>
                            </div>
                        </div>
                    </div>
                `

                contentChatboxList.insertAdjacentHTML('beforeend', template)
                $('.msgr-main-content-chatbox-list').overlayScrollbars().scroll({ y: '100%' })
            } else {
                // Photo or docs
                template = `
                    <div class="msgr-main-content-chatbox-list-item">
                        <span class="${(mPrevDate.textContent == mDate) && (mPrevTime.textContent == mTime) ? 'stamp-hide' : ''}">
                            <span class="${mPrevDate.textContent == mDate ? 'stamp-hide' : ''}">${mDate}</span> 
                            <span class="${mPrevTime.textContent == mTime ? 'stamp-hide' : ''}">${mTime}</span>
                        </span>

                        <p style="display: ${mMsg.data.type == 'GROUP' ? (id === M_ID ? 'none;' : 'block;') : 'none;'} color:#999; margin:0 0 .5rem;">${name}</p>
                        <div class="msgr-main-content-chatbox-list-item-details ${id === M_ID ? 'owner' : ''}">
                            <img class="img-circle" src="${mMsg.data.type == 'GROUP' ? (sex == 'M' ? '/img/1.png' : '/img/2.png') : src}" alt="User image">
                            <div class="msgr-main-content-chatbox-list-item-details-content">
                                ${msg.file_type === 'image' ? `<img src="${msg.file_thumb}" alt="${msg.file_name}" style="border: 1.5rem solid #09f; border-radius: 2.5rem; max-width:70%;">` : `<p><a href="${msg.file_path}" target="_blank" style="color: ${id === M_ID ? '#fff' : '#0099ff'} !important; text-decoration:underline;">${msg.file_name}</a></p>`}
                            </div>
                        </div>
                    </div>
                `

                contentChatboxList.insertAdjacentHTML('beforeend', template)
                $('.msgr-main-content-chatbox-list').overlayScrollbars().scroll({ y: '100%' })
            }

        })

        btnChatboxPhoto.setAttribute('data-conn', 'SIMPLE')
        btnChatboxFile.setAttribute('data-conn', 'SIMPLE')

        const tabImage = $('#tab-image')
        tabImage.nanogallery2('destroy')

        const mImages = await axios.get(`${BK_HTTP_URL}/api/thread/${cId}?expand=images`)
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

        const mDocs = await axios.get(`${BK_HTTP_URL}/api/thread/${cId}?expand=docs`)
        mDocs.data.docs.map(doc => {
            tabDocs.append(`
                <li style="margin: 1rem 0;">
                    <a href="${doc.file_path}" target="_blank" style="text-decoration:underline;" title="${doc.file_name}">${doc.file_name}</a> <br/>
                    <span class="label label-default">${moment(doc.created_at).format('MMM DD, YYYY hh:mm a')}</span>
                </li>
            `)
        })
    }
}

let mConn = {}
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

const chatConfirm = params => {
    // Hide chat button
    params.style.display = 'none'

    // Get refs.
    const h4 = params.parentElement
        .previousElementSibling.children[1].children[0]
    const img = params.parentElement
    .previousElementSibling.children[0]

    const mId = h4.dataset.id
    const mName = h4.textContent
    const mImg = img.src

    const mMembers = [
        { member_id: M_ID, role: 'ADMIN' },
        { member_id: mId, role: 'MEMBER' }
    ]

    // Send request
    axios.post(`${BK_HTTP_URL}/api/thread`, {
        type: 'SIMPLE',
        name: `${M_NAME}:${mName}`,
        members: mMembers
    }).then(resp => {

        // Render template
        const template = `
            <div class="msgr-sidebar-list-item" onclick="connect(this, '${resp.data.id}', 'SIMPLE')">
                <div class="msgr-sidebar-list-item-content">
                    <img class="img-circle" src="${mImg}" alt="User image">                        
                    <div class="msgr-sidebar-list-item-content-details">
                        <h4>${mName}</h4>
                        <p>-</p>
                    </div>
                </div>

                <div class="msgr-sidebar-list-item-settings">
                    <span>-</span>
                </div>
            </div>
        `
        $(`.msgr-sidebar-list > .os-padding > .os-viewport > .os-content`).prepend(template)

    }).catch(err => console.error(err))
}

const groupConfirm = params => {

    const h4 = params.parentElement
        .previousElementSibling.children[1].children[0]

    const mId = h4.dataset.id
    const mName = h4.textContent
    const mMembers = [
        { member_id: M_ID, role: 'ADMIN' },
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
    }).then(async res => {
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
                        return fetch(`${BK_HTTP_URL}/api/thread`, {
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
                // Render template
                const template = `
                    <div class="msgr-sidebar-list-item" onclick="connect(this, '${res.value.id}', 'GROUP')">
                        <div class="msgr-sidebar-list-item-content">
                            <img class="img-circle" src="${FR_HTTP_URL}/img/3.png" alt="User image">                        
                            <div class="msgr-sidebar-list-item-content-details">
                                <h4>${res.value.result}</h4>
                                <p>-</p>
                            </div>
                        </div>

                        <div class="msgr-sidebar-list-item-settings">
                            <span>-</span>
                        </div>
                    </div>
                `
                
                $(`.msgr-sidebar-list > .os-padding > .os-viewport > .os-content`).prepend(template)
            })
        } else {
            // Existing Group
            const req = await axios.get(`${BK_HTTP_URL}/api/member/${M_ID}?expand=threads_group`)
            const mGroups = Object
                .keys(req.data.threads_group)
                .map(x => req.data.threads_group[x])
                .filter(x => !(x.members.find(m => m.id === mId)))
                .map(x => ({ id: x.id, name: x.global_config.name }))
                .reduce((acc, cur) => {
                    acc[cur.id] = cur.name
                    return acc
                }, {})

            if(!_.isEmpty(mGroups)) {
                swal({
                    title: 'Select an existing group ..',
                    input: 'select',
                    inputOptions: mGroups,
                    showCancelButton: true,
                    allowOutsideClick: false,
                }).then(async res => {
                    if(res.value) {
                        const resp = await axios.post(`${BK_HTTP_URL}/api/thread-member`, {
                            thread_id: res.value,
                            member_id: mId,
                            role: 'MEMBER'
                        })

                        if(resp.data) {
                            // Rerender about
                            $(`<li>${mName}}</li>`).insertBefore("#tab-about > button")
                            
                            // @TOOD: Emit to backend to notify.
                            // GROUP.emit('join-chat-group', { id: cId })
                        }

                        console.log(resp.data)
                    }
                })
            }
        }
    })
}

const checkSWSupport = _ => {
    let isSupported = true

    if (!('serviceWorker' in navigator)) {
        console.warn('No Service Worker support!')
        isSupported = false
    }

    if (!('PushManager' in window)) {
        console.warn('No Push API Support!')
        isSupported = false
    }

    return isSupported
}

const initialize = async _ => {
    axios.get(`${BK_HTTP_URL}/api/member/${M_ID}?expand=threads`, { headers: {'Access-Control-Allow-Origin': '*'} }).then(resp => {
        const template = resp.data.threads.map(th => {
            // Filter user list.
            Array.from(userList.getElementsByClassName('msgr-main-content-tools-user-list-item'))
            .forEach(mItem => {
                if(mItem.children[0].children[1].children[0]
                    .textContent.toLowerCase() == th.name.toLowerCase()
                ) {
                    mItem.children[1].children[0].style.display = 'none'
                }
            })

            // Render list
            return `
                <div class="msgr-sidebar-list-item" onClick="connect(this, '${th.id}', '${th.type}')">
                    <div class="msgr-sidebar-list-item-content">
                        <img class="img-circle" src="${FR_HTTP_URL}/img/${th.type == 'GROUP' ? '3' : th.sex == 'M' ? '1' : '2'}.png" alt="User image">                        
                        <div class="msgr-sidebar-list-item-content-details">
                            <h4 style="font-weight: ${(th.message && th.message.unread) && (M_ID !== th.message.sent_by) ? 'bold;' : 'normal;'}">${th.name}</h4>
                            <p style="font-weight: ${(th.message && th.message.unread) && (M_ID !== th.message.sent_by) ? 'bold; color:#000;' : 'normal;'}">${th.message ? strTruncate(th.message.latest, 20) : '-'}</p>
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

    initConn(M_ID, M_NAME)

    contentChatboxInputBox.addEventListener('keydown', e => {
        if(e.keyCode === 13 && !e.shiftKey) {
            e.preventDefault()
    
            const timestamp = moment().format('YYYY-MM-DD HH:mm:ss')
            const message = e.target.value

            if(message) {
                if(mConn.type == 'GROUP') {
                    GROUP.emit('chat', { cId: mConn.cId, uId: M_ID, message, timestamp })
                } else {
                    SIMPLE.emit('chat', { cId: mConn.cId, uId: M_ID, message, timestamp })
                }

                contentChatboxInputBox.value = ''
            }
        }
    })
}

document.addEventListener('DOMContentLoaded', async _ => {

    if(checkSWSupport()) {
        console.log('Registering service worker ..')

        const register = await navigator.serviceWorker
            .register(`${FR_HTTP_URL}/js/base/worker.js`)
            .catch(err => console.error(err))

        const subscription = await register.pushManager
            .subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(PUB_VAPID_KEY)
            })
            .catch(err => console.error(err))

        // @TODO: Handle this on backend.
        // axios.post(`${SOCKET_HTTP_URL}/msgr`, { subscription })
    }

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

    btnDetailsHamburg = document.querySelector('#btn-details-hamburg')
    btnEmployeeSearch = document.querySelector('#btn-employee-search')

    inputChatSearch = document.querySelector('#input-chat-search')
    inputEmployeeSearch = document.querySelector('#input-employee-search')

    userList = document.querySelector('.msgr-main-content-tools-user-list')

    const toolsHeaderContainer = document.querySelector('.msgr-main-content-tools-user-header-container')
    const chatSearchContainer = document.querySelector('#input-chat-search-container')

    let isOpen = false
    btnDetailsHamburg.addEventListener('click', e => {
        if(isOpen) {
            isOpen = false
            $('.msgr-main-content-chatbox').css('width', '75%')
            $('.msgr-main-content-tools').css('display', 'block')
        } else {
            isOpen = true
            $('.msgr-main-content-chatbox').css('width', '100%')
            $('.msgr-main-content-tools').css('display', 'none')
        }
    })

    //Search Filters
    inputChatSearch.addEventListener('keyup', e => initSearch(sidebarList, 'msgr-sidebar-list-item', e.target.value))
    inputEmployeeSearch.addEventListener('keyup', e => initSearch(userList, 'msgr-main-content-tools-user-list-item', e.target.value))

    inputEmployeeSearch.addEventListener('focusout', e => {
        toolsHeaderContainer.style.display = 'flex';
        chatSearchContainer.style.display = 'none';
        // inputEmployeeSearch.value = ''
    })
    
    btnEmployeeSearch.addEventListener('click', e => {
        toolsHeaderContainer.style.display = 'none';
        chatSearchContainer.style.display = 'flex';
        inputEmployeeSearch.focus()
    })

    if(M_ID && M_NAME) {
        initialize()
    } else {
        Swal.mixin({
            input: 'text',
            confirmButtonText: 'Next &rarr;',
            showCancelButton: true,
            progressSteps: ['1', '2'],
            inputValidator: (value) => !value && 'You need to write something!'
        }).queue([
            {
                title: 'Question 1',
                text: 'Enter your ID'
            },
            {
                title: 'Question 2: For Test Purpose',
                text: 'Enter your name'
            }
        ]).then((result) => {
            if (result.value) {
                M_ID  = result.value[0]
                M_NAME  = result.value[1]

                initialize()
            }
        })
    }
})