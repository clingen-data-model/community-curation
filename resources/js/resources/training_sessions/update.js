import moment from 'moment'

const updateTrainingSession = function (id, data) {
    console.info('updateTrainingSession', data);
    data.starts_at = moment(data.starts_at).toISOString();
    data.ends_at = moment(data.ends_at).toISOString();
    return window.axios.put('/api/training-sessions/'+id, data);
}

export default updateTrainingSession;