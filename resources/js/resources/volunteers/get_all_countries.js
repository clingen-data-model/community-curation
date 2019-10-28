const getAllCountries = async function () {
    let data = JSON.parse(localStorage.getItem('countries'));

    if (!data) {
        data = await window.axios.get('/api/countries').then(response => {
            localStorage.setItem('countries', JSON.stringify(response.data.data))
            return response.data.data
        });
    }

    return data;
}

export default getAllCountries;