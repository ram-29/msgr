const processResult = (data, params) => ({
    results: data.users.reduce((acc, user, i) => {
        if(i == 0) acc.push({ text: (user.office) ? user.office.trim() : 'No office specified', children: [] })

        const idx = acc.findIndex(u => u.text === user.office)

        if(idx == -1) {
            acc.push({
                text: (user.office) ? user.office.trim() : 'No office specified',
                children: [{ 
                    id: user.id,
                    text: user.full_name.trim().toUpperCase(),
                }]
            })
        } else {
            acc[idx].children.push({
                id: user.id,
                text: user.full_name.trim().toUpperCase()
            })
        }

        return acc
    }, [])
})

const formatTemplate = (users, i) => {
if (users.loading) return users.text

    const template = `
        <div style="overflow:hidden;">
            <div class="row" style="display:flex; align-items:center; padding:1rem 0;">
                <span style="margin-left: 2rem;">${users.text}</span>
            </div>
        </div>
    `

    return template
}

const templateSelect = user => user.text
