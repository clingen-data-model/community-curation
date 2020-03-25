const getAllAptitudes = async function() {
    let data = JSON.parse(localStorage.getItem('aptitudes'));

    if (!data) {
        await window.axios.get('/api/aptitudes').then(response => {
            data = response.data.data;
            localStorage.setItem('aptitudes', JSON.stringify(data))
        });
    }

    return data;
}

export default getAllAptitudes;