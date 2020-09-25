export default function(userId, preferenceName, value) {
    const data = {
        'value': value
    };
    return window.axios.put('/api/users/' + userId + '/preferences/' + preferenceName, data)
        .then(response => {
            sessionStorage.setItem('user', JSON.stringify(response.data.data));
            return response.data.data;
        })
}