import moment from 'moment';

export default function(trainingId, completionDate) {
    const data = {
        trained_at: (completionDate) ? moment(completionDate).format('YYYY-MM-DD') : null
    };
    return window.axios.put('/api/trainings/' + trainingId, data)
}