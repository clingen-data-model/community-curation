import Volunteer from './../../entities/volunteer'

const findVolunteer = async function (volunteerId) {
    return await window.axios.get('/api/volunteers/'+volunteerId)
        .then(response => {
            let volunteer = response.data.data
            return new Volunteer(volunteer);
        });
}

export default findVolunteer;