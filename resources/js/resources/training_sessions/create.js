const createTrainingSession = function (trainingSession) {
    trainingSession.starts_at = trainingSession.starts_at.format('YYYY-MM-DD HH:mm:ss')
    trainingSession.ends_at = trainingSession.ends_at.format('YYYY-MM-DD HH:mm:ss')
    return window.axios.post('api/training-sessions', trainingSession);
}

export default createTrainingSession;