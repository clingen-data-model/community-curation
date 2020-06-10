import queryStringFromParams from '../../http/query_string_from_params'

const getPageOfVolunteers = function (params) {
    const url = '/api/volunteers' + '?' + queryStringFromParams(params);
    return window.axios.get(url);
}

export default getPageOfVolunteers;