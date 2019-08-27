const getAllCurationActivities = async function () {
    // let cacheLifeTime = 60*10*1000;
    // let data = JSON.parse(localStorage.getItem('expert-panels'));
    let data = [];
    // if (!data) {
        window.axios.get('/api/expert-panels').then(response => {
            data = response.data.data
            // localStorage.setItem('expert-panels', JSON.stringify(data))
        });
    // }

    return data;
}

export default getAllCurationActivities;