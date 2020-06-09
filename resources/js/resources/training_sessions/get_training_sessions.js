import queryStringFromParams from '../../http/query_string_from_params'
import moment from 'moment'

const getTrainingSessions = async function (params) {
    const url = '/api/training-sessions'+'?'+queryStringFromParams(params)
    
    let response = await window.axios(url);
    return response.data.map(item => {
        item.starts_at = moment(item.starts_at)
        item.ends_at = moment(item.ends_at)
        return item;
    });
}

export default getTrainingSessions;