const BASE_URL = `http://bk.msgr.io`
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

const initUpload = e => {
    swal({
        title: 'Upload files here ..',
        html: `<input id="filepond" type="file" class="filepond" name="filepond" multiple data-max-files="10"/>`,
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
    const filepond = FilePond.create(document.querySelector('#filepond'), {
        instantUpload: false,
        server: {
            url: null,
            process: (fieldName, file, metadata, load, error, progress, abort) => {
                
                load('OK')

                const fileReader = new FileReader()
                const slice = file.slice(0, 100000)

                switch(e.getAttribute('data-conn')) {
                    case 'SIMPLE':
                        fileReader.readAsArrayBuffer(slice)
                        fileReader.onload = e => {
                            SIMPLE.emit('upload-img', {
                                name: file.name, 
                                type: file.type, 
                                size: file.size, 
                                data: fileReader.result 
                            })
                        }
                    break
                    case 'GROUP':
                        // @TODO: Handle in backend
                        GROUP.emit('upload-img', { file })
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

let id, name, SIMPLE, GROUP
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

let mConn = {};
const connect = (el, id, type) => {

    switch(type) {
        case 'SIMPLE':
            SIMPLE.emit('join-room', { id })
            mConn = { id, type: 'SIMPLE' }
            btnChatboxPhoto.setAttribute('data-conn', 'SIMPLE')
        break;
        case 'GROUP':
            GROUP.emit('join-room', { id })
            mConn = { id, type: 'GROUP' }
            btnChatboxPhoto.setAttribute('data-conn', 'GROUP')
        break;
    }

    initUI(el)
}

document.addEventListener('DOMContentLoaded', _ => {

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
    ]).then(result => {

        const x = result.value.filter(x => x)
        if (!(x === undefined || x.length == 0)) {

            // id = result.value[0]
            // name = result.value[1]

            id = 'd49a82aa-a674-454c-8398-2d643403e097'
            name = 'John Doe'

            axios.get(`${BASE_URL}/api/member/${id}?expand=threads`).then(resp => {
                const template = resp.data.threads.map(th => {
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
                                <button type="button" id="btn-list-item-setting" class="btn btn-default btn-sm">
                                    <i class="fa fa-cog fa-fw"></i>
                                </button>
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
            
                    const timestamp = moment().format('MMM DD, YYYY, hh:mm a')
                    const message = e.target.value

                    if(mConn.type == 'GROUP') {
                        GROUP.emit('chat', { cId: mConn.id, uId: id, message, timestamp })
                    }

                    SIMPLE.emit('chat', { cId: mConn.id, uId: id, message, timestamp })
                }
            })
        }
    })
})