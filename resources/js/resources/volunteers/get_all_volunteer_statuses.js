const getAllVolunteerStatuses = async function () {
    console.log('volunteer-statuses');
    let data = JSON.parse(localStorage.getItem('volunteer-statuses'));

    if (!data) {
        data = await window.axios.get('/api/volunteer-statuses').then(response => {
            localStorage.setItem('volunteer-statuses', JSON.stringify(response.data.data))
            return response.data.data
        });
    }

    return data;
}

export default getAllVolunteerStatuses;