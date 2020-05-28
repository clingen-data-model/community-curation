const getAllAptitudes = async function() {
    let data = JSON.parse(sessionStorage.getItem('impersonatable-users'));

    if (!data) {
        await window.axios.get('/api/impersonatable-users').then(response => {
            data = response.data;
            sessionStorage.setItem('impersonatable-users', JSON.stringify(data))
        });
    }

    return data;
}

export default getAllAptitudes;