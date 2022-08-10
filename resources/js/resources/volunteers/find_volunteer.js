import Volunteer from './../../entities/volunteer'

const findVolunteer = async function (volunteerId) {
    return await window.axios.get('/api/volunteers/'+volunteerId)
        .then(response => {
            const volunteer = new Volunteer(response.data.data);
            return volunteer;
        });
}

export default findVolunteer;