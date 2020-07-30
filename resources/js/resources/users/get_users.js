import queryStringFromParams from '../../http/query_string_from_params'
import User from '../../user';

export default function getUsers(params) {
    const baseUrl = '/api/users';
    return window.axios.get(`${baseUrl}?${queryStringFromParams(params)}`)
        .then(response => {
            response.data.data = response.data.data.map(userData => new User(userData));
            return response;
        });
}