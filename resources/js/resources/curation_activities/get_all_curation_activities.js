const getAllCurationActivities = async function () {
    let data = JSON.parse(localStorage.getItem('curation-activities'));

    if (!data) {
        window.axios.get('/api/curation-activities').then(response => {
            console.log('requesting curation activities');
            data  = response.data.data
            localStorage.setItem('curation-activities', JSON.stringify(data))
        });
    }

    return data;
}

export default getAllCurationActivities;