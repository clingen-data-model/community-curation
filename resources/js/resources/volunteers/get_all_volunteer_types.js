const getAllVolunteerTypes = async function () {
    console.log('volunteer-types');
    let data = JSON.parse(localStorage.getItem('volunteer-types'));

    if (!data) {
        data = await window.axios.get('/api/volunteer-types').then(response => {
            localStorage.setItem('volunteer-types', JSON.stringify(response.data.data))
            return response.data.data
        });
    }

    return data;
}

export default getAllVolunteerTypes;