import queryStringFromParams from '../http/query_string_from_params'

const baseUrl = '/api/notes';

export function all (params) {
    return window.axios.get(`${baseUrl}?${queryStringFromParams(params)}`);
}

export function create (data) {
    return window.axios.post(baseUrl, data);
}

export function show (id) {
    return window.axios.get(`${baseUrl}/${id}`);
}

export function update (id, data) {
    return window.axios.put(`${baseUrl}/${id}`, data);
}

export function destroy (note) {
    return window.axios.delete(`${baseUrl}/${note.id}`);
}