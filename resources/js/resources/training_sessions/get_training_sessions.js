import queryStringFromParams from '../../http/query_string_from_params'
import moment from 'moment'
import TrainingSession from '../../entities/training_session'

const getTrainingSessions = async function (params) {
    const url = '/api/training-sessions'+'?'+queryStringFromParams(params)
    
    let response = await window.axios(url);
    return response.data.data.map(item => {
        return new TrainingSession(item);
    });
}

export default getTrainingSessions;