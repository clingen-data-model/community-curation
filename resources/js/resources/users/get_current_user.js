const getCurrentUser = async function() {
    let user = JSON.parse(sessionStorage.getItem('user'));

    try {
        user = await window.axios
            .get('/api/users/current')
            .then(response => response.data.data)
    } catch (error) {
        if (error.response && error.response.status == 401) {
            return;
        }
        throw error
    }

    return user;
}

export default getCurrentUser;