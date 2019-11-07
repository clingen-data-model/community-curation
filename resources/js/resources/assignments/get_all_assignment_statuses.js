const getAllAssignmentStatuses = async function () {
    let data = JSON.parse(localStorage.getItem('assignment-statuses'));

    if (!data) {
        data = await window.axios.get('/api/assignment-statuses').then(response => {
            localStorage.setItem('assignment-statuses', JSON.stringify(response.data.data))
            return response.data.data
        });
    }

    return data;
}

export default getAllAssignmentStatuses;