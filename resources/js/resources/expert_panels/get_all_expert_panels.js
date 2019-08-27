const getAllCurationActivities = async function () {
    return await window.axios.get('/api/expert-panels').then(response => response.data.data)
}

export default getAllCurationActivities;