import moment from 'moment';

export default function(trainingId, completionDate) {
    const data = {
        trained_at: (completionDate) ? moment(completionDate).toISOString() : null
    };
    return window.axios.put('/api/trainings/' + trainingId, data)
}