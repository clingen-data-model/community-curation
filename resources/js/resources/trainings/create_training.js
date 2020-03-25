const createTraining = function (data) {
    return window.axios.post('/api/trainings', data)
        .catch(error => {
            console.debug(error)
            alert('There was a problem saving the training.  If the problem persists please contact the administrator')
            return error;
        });
;
}

export default createTraining