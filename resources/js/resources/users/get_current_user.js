const getCurrentUser = async function () {
    let user = JSON.parse(sessionStorage.getItem('user'));

    if (!user) {
        console.log('get current user from session storage');
        user = await window.axios.get('/api/users/current').then(response => response.data)
        sessionStorage.setItem('user', JSON.stringify(user));
    }

    return user;
}

export default getCurrentUser;