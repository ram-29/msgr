let M_OFFSET = 1;

;(_ => {
    moment.createFromInputFallback = function(config) { config._d = new Date(config._i); }
    $('[data-toggle="tooltip"]').tooltip({ trigger: 'hover' })
    
    // User List
    const mToolsUserList = $('.msgr-main-content-tools-user-list').overlayScrollbars({
        callbacks: {
            onScrollStop: e => {
                const scrollInfo = mToolsUserList.scroll()

                if(scrollInfo.ratio.y === 1) {
                    console.log("Im at bottom")
                }
            }
        }
    }).overlayScrollbars();

    // Chatbox List
    const mChatboxList = $('.msgr-main-content-chatbox-list').overlayScrollbars({
        callbacks: {
            onScrollStop: e => {
                const scrollInfo = mChatboxList.scroll()

                if (scrollInfo.ratio.y === 0) {
                    $('#spinner-container').removeClass('spinner-hide').addClass('spinner-show')

                    axios.get(`${BK_URL}/api/thread/${mConn.cId}?expand=messages&offset=${M_OFFSET}`).then(mMsg => {
                        mMsg.data.messages.map((msg, idx) => {

                            let template
                            const src = contentChatboxHeaderImg.getAttribute('src')

                            const mDate = moment(msg.created_at).format('MMM DD, YYYY')
                            const mTime = moment(msg.created_at).format('hh:mm a')

                            const mPrevDate = $('#spinner-container').next()[0].firstElementChild.firstElementChild
                            const mPrevTime = $('#spinner-container').next()[0].firstElementChild.lastElementChild

                            let id, name, sex
                            if(msg.member) {
                                id = msg.member.id
                                name = msg.member.name
                                sex = msg.member.sex
                            }

                            // NOTIF
                            if(msg.type == 'NOTIF') {
                                template  = `
                                    <div class="msgr-main-content-chatbox-list-item">
                                        <span style="display:flex; align-items:center; flex-direction:column;">
                                            <span>
                                                <span>${mDate}</span> at
                                                <span>${mTime}</span>
                                            </span>
                                            <span>${msg.text}</span>
                                        </span>
                                    </div>
                                `
                            }

                            // MSG
                            if(msg.type == 'MSG') {
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
                                }
                            }

                            $('#spinner-container').after(template)

                        })

                        $('#spinner-container').removeClass('spinner-show').addClass('spinner-hide')
                    })
                    M_OFFSET++

                } else {
                    $('#spinner-container').removeClass('spinner-show').addClass('spinner-hide')
                }
            }
        }
    }).overlayScrollbars();

    $('.msgr-main-content-tools-user-list').overlayScrollbars({})
    $('.tab-pane').overlayScrollbars({})
})();

const strTruncate = (str, len) => {
    return (str.length > len) ?
        `${str.substring(0, len)} ...` : str
}

const brwConfirm = url => _ => {
    if (window.history && history.pushState) {
        if (document.location.pathname === url) {
            if (history.state == null) {
                history.pushState({'status': 'ongoing'}, null, null)
            }
            window.onpopstate = function(event) {
                swal({
                    title: 'Are you sure?',
                    text: 'You are about to leave this page.',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, leave now!'
                }).then((result) => {
                    if (result.value) {
                        window.onpopstate = null
                        history.back()
                    } else {
                        history.pushState(null, null, null)
                    }
                })
            }
        }
    }
}

const brdConfirm = e =>  {
    e.preventDefault()

    swal({
        title: 'Are you sure?',
        text: 'You are about to leave this page.',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, leave now!'
    }).then((result) => {
        if (result.value) {
            window.location.href = e.target.href
        }
    })
}

const initLoad = (cbs = []) => {
    if(cbs.length){
        window.onload = function() {
            cbs.forEach(cb => cb())
        }
    }
}

const ajx = (
    params = {
        url: '',
        data: null
    }, cb = () => {}, data = {}
) => {
    $.post(params.url, params.data)
    .done(resp => $.isEmptyObject(data) ? cb(resp) : cb(resp, data))
    .fail((xhr, stat, err) => console.error(xhr.responseText))
}

const reloadPage = resp => {
    if (resp.success) location.reload()
}

const submitForm = (form, cb) => {
    $(form)
    .on('beforeSubmit', () => ajx({ url : form.action, data: $(form).serialize() }, cb))
    .on('submit', e => e.preventDefault())
}

const formConfirm = form => {
    swal({
        title: 'Are you sure?',
        text: "Make sure the data you are submitting is uniform.",
        type: 'warning',
        showCloseButton: true,
        focusConfirm: false,
        allowOutsideClick: false,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText:
            'Submit <i class="fa fa-paper-plane-o"></i>',
        confirmButtonAriaLabel: 'Submit',
        cancelButtonText:
            'Cancel <i class="fa fa-ban"></i>',
        cancelButtonAriaLabel: 'Cancel',
    }).then((result) => {
        if (result.value) {
            $(`#${form}`).submit()
        }
    })
}

const hideModal = resp => {
    if (resp.success) clearModal().then(res => location.reload())
}

const showModal = (resp, data) => {
    $('#modal-contents').html(resp)
    $('#modal-title').html(data.text)
    $('#modal').modal('show')
}

const addOption = (resp) => {
    if(resp.success) {
        $('#'+resp.el).append(
            $('<option selected></option>')
            .attr('value', resp.data.id).text(resp.data.name)
        )
        
        clearModal().then(_ => {
            // Crappy patch, must run another callback here.
            if($('#ldip-planning_period_id').length){
                $('#ldip-planning_period_id').trigger('select2:select')
            }
        })
    } else {
        clearModal().then(_ => swal(
            'Ooopss!',
            'You have previously entered this value!',
            'error'
        ))
    }
}

const clearModal = () => new Promise((resolve, reject) => {
    $('#modal').modal('hide')
    $('#modal-contents').empty()
    resolve()
})

const removeItem = (target, cb = () => {}, opts = {}) => {
    if (!($(target).closest('tbody').find('tr').length === 1)) {
        target.parentElement.parentElement.remove()
        $.isEmptyObject(opts) ? cb() : cb(opts)
    }
}

const randomize = () => {
    let num = Date.now()
    
    // If created at same millisecond as previous
    if (num <= randomize.previous) {
        num = ++randomize.previous
    } else {
        randomize.previous = num
    }
    
    return num
}

const afHack = form => {
    // Ensures all attributes: id & input props are the same.
    const attrs = $(`#${form}`).data('yiiActiveForm').attributes
    attrs.forEach(attr => { attr.id = attr.input.replace(/[^0-9a-zA-Z-_]/gi, '') })
}

const nextString = str => {
    if (! str) return 'A'

    let tail = ''
    let i = str.length -1
    let char = str[i]
    
    while (char === 'Z' && i > 0) {
        i--
        char = str[i]
        tail = 'A' + tail
    }
    
    if (char === 'Z')
        return 'AA' + tail

    return str.slice(0, i) + String.fromCharCode(char.charCodeAt(0) + 1) + tail
}

const formatPrice = p => {
    return Number(p).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
}

const reNumber = str => {
    return str ? parseFloat(str.split(',').join('')) : 0
}

const urlBase64ToUint8Array = base64String => {
    const padding = '='.repeat((4 - base64String.length % 4) % 4)
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/')
  
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length)
  
    for (let i = 0; i < rawData.length; ++i) {
      outputArray[i] = rawData.charCodeAt(i)
    }

    return outputArray
}
