import moment from 'moment';

export default function (trainingId, completionDate) {
    const data = {
        completed_at: moment(completionDate).format('YYYY-MM-DD')
    };
    return window.axios.put('/api/trainings/' + trainingId, data)
}