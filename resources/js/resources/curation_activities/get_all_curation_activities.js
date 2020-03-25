const getAllCurationActivities = async function() {
    let data = JSON.parse(localStorage.getItem('curation-activities'));

    if (!data) {
        await window.axios.get('/api/curation-activities').then(response => {
            data = response.data.data
            localStorage.setItem('curation-activities', JSON.stringify(data))
        });
    }

    return data;
}

export default getAllCurationActivities;