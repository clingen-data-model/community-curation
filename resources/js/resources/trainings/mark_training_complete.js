import moment from 'moment';

export default function (trainingId, completionDate) {
    const data = {
        trained_at: moment(completionDate).format('YYYY-MM-DD')
    };
    return window.axios.put('/api/trainings/' + trainingId, data)
}