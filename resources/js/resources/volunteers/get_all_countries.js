const getAllCountries = async function () {
    // let data = JSON.parse(localStorage.getItem('countries'));

    // if (!data || !data.map(d => d.name).includes('Taiwan')) {
        return await window.axios.get('/api/countries').then(response => {
            localStorage.setItem('countries', JSON.stringify(response.data.data))
            return response.data.data
        });
    // }

    // data = data.sort((a, b) => (a.name > b.name) ? 1 : (a.name < b.name) ? -1 : 0);

    // return data;
}

export default getAllCountries;