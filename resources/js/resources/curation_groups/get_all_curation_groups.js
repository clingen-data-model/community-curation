const getAllCurationActivities = async function () {
    return await window.axios.get('/api/curation-groups').then(response => response.data.data)
}

export default getAllCurationActivities;