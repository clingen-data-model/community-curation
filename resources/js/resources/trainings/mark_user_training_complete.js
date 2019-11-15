import moment from 'moment';

export default function (userTrainingId, completionDate) {
    const data = {
        completed_at: moment(completionDate).format('YYYY-MM-DD')
    };
    return window.axios.put('/api/user-trainings/' + userTrainingId, data)
}