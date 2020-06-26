const createTrainingSession = function (data) {
    data.starts_at = moment(data.starts_at).toISOString()
    data.ends_at = moment(data.ends_at).toISOString()  
    return window.axios.post('api/training-sessions', data);
}

export default createTrainingSession;