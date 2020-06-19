import moment from 'moment'

const updateTrainingSession = function (id, trainingSession) {
    trainingSession.starts_at = moment(trainingSession.starts_at).toISOString();
    trainingSession.ends_at = moment(trainingSession.ends_at).toISOString();
    return window.axios.put('/api/training-sessions/'+id, trainingSession);
}

export default updateTrainingSession;