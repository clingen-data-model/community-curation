const getAllVolunteers = async function () {
    console.log('getting all volunteers');
    let data = [];

    window.axios.get('/api/volunteers')
        .then(response => {
            console.log('got volunteers')
            console.log(response.data.data);
            data = response.data.data
        });

    return data;
}

export default getAllVolunteers;