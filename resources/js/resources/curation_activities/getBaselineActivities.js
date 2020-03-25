const getBaselineActivities = async function() {
    let data = JSON.parse(localStorage.getItem('baseline-activities'));

    if (!data) {
        await window.axios.get('/api/curation-activities?only_baseline=1').then(response => {
            data = response.data.data
            localStorage.setItem('baseline-activities', JSON.stringify(data))
            return data;
        });
    }

    return data;
}

export default getBaselineActivities;