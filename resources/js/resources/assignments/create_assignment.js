const createAssignment = function (data) {
    return window.axios.post('/api/assignments', {
            assignable_type: data.assignable_type,
            assignable_id: data.assignable_id,
            user_id: data.user_id
        })
        .catch(error => {
            console.debug(error)
            alert('There was a problem saving the assignment.  If the problem persists please contact the administrator')
            return error;
        });
;
}

export default createAssignment