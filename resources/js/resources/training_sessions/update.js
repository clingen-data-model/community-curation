import moment from 'moment'

const updateTrainingSession = function (id, trainingSession) {
    trainingSession.starts_at = moment(trainingSession.starts_at).format('YYYY-MM-DD HH:mm:ss')
    trainingSession.ends_at = moment(trainingSession.ends_at).format('YYYY-MM-DD HH:mm:ss')
    return window.axios.put('api/training-sessions/'+id, trainingSession);
}

export default updateTrainingSession;