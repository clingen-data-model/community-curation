import queryStringFromParams from '../../http/query_string_from_params';

export default async function(params) {

    if (typeof params === 'object') {
        params = queryStringFromParams(params);
    }
    return await window.axios.get('/api/volunteers/metrics' + ((params) ? '?' + params : ''))
        .then(response => response.data)
}