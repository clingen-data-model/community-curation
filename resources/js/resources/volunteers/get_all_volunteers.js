import queryStringFromParams from '../../http/query_string_from_params';

const getAllVolunteers = async function(params) {
    if (typeof params === 'object') {
        params = queryStringFromParams(params);
    }
    return await window.axios.get('/api/volunteers' + ((params) ? '?' + params : ''))
        .then(response => response.data.data)
}

export default getAllVolunteers;