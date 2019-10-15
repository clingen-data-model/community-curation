const findVolunteer = async function (volunteerId) {
    return await window.axios.get('/api/volunteers/'+volunteerId)
        .then(response => {
            let volunteer = response.data.data
            volunteer.isComprehensive = function () {
                return this.volunteer_type_id == 2;
            }
            volunteer.isBaseline = function () {
                return this.volunteer_type_id == 1;
            }
            return volunteer
        });
}

export default findVolunteer;