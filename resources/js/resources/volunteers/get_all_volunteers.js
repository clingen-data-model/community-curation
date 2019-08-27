const getAllVolunteers = async function () {
    return await window.axios.get('/api/volunteers').then(response => response.data.data)
}

export default getAllVolunteers;