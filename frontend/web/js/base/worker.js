self.addEventListener('push', e => {
    const data = e.data.json()

    self.registration.showNotification(data.title, {
        body: 'Notified by Ram Delatina',
        icon: 'https://image.ibb.co/frY0Fd/tmlogo.png'
    })
})