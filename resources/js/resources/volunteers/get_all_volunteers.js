const findAllVolunteers = function () {
    return window.axios.get('/api/volunteers/')
        .then(response => {
            return response.data
        })
}

export default findAllVolunteers();