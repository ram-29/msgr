(_ => {
    $('[data-toggle="tooltip"]').tooltip({ trigger: 'hover' })
    
    // $('.msgr-sidebar-list').overlayScrollbars({})
    const mChatboxList = $('.msgr-main-content-chatbox-list').overlayScrollbars({
        callbacks: {
            onScroll: e => {
                const scrollInfo = mChatboxList.scroll()

                if (scrollInfo.ratio.y === 0) {
                    // TODO: Request new data messages here.
                }
            }
        }
    }).overlayScrollbars()

    $('.msgr-main-content-tools-user-list').overlayScrollbars({})
    $('.tab-pane').overlayScrollbars({})
})()

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
