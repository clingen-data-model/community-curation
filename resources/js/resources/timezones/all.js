const getAllTimeZones = async function () {
    let data = JSON.parse(localStorage.getItem('timezones'));
    if (!data) {
        data = await window.axios.get('/api/timezones')
                .then(response => response.data.data);
        localStorage.setItem('timezones', JSON.stringify(data));
    }

    return data.filter(tz => tz !== 'UTC');
}

export default getAllTimeZones;