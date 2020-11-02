import store from '../../store/index'

const getAllVolunteerStatuses = async function() {
    // let data = JSON.parse(localStorage.getItem('volunteer-statuses'));

    // // if (!data) {
    // window.axios.get('/api/volunteer-statuses').then(response => {
    //     localStorage.setItem('volunteer-statuses', JSON.stringify(response.data.data))
    //     return response.data.data
    // });
    // // }

    // return data;
    const statusArray = Object.keys(store.state.configs.volunteers.statuses)
        .map(name => {
            return {
                'name': name,
                id: store.state.configs.volunteers.statuses[name]
            }
        });

    console.log(statusArray);
    return statusArray;
}

export default getAllVolunteerStatuses;