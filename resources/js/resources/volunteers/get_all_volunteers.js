const getAllVolunteers = async function (params) {
return await window.axios.get('/api/volunteers'+((params) ? '?'+params : ''))
                .then(response => response.data.data)
}

export default getAllVolunteers;