const createTrainingSession = function (trainingSession) {
    trainingSession.starts_at = trainingSession.starts_at.toISOString()
    trainingSession.ends_at = trainingSession.ends_at.toISOString()  
    return window.axios.post('api/training-sessions', trainingSession);
}

export default createTrainingSession;