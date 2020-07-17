import queryStringFromParams from '../../http/query_string_from_params'

const getCurationGroups = function (params) {
    const url = '/api/curation-groups' + '?' + queryStringFromParams(params);
    return window.axios.get(url);
}

export default getCurationGroups;