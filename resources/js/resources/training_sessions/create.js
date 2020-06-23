const createTrainingSession = function (trainingSession) {
    console.log(trainingSession.starts_at);
    trainingSession.starts_at = moment(trainingSession.starts_at).toISOString()
    trainingSession.ends_at = moment(trainingSession.ends_at).toISOString()  
    return window.axios.post('api/training-sessions', trainingSession);
}

export default createTrainingSession;