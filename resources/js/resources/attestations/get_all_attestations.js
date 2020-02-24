export default async function(params) {
    const baseUrl = '/api/attestations';

    return await axios.get(baseUrl + ((params) ? '?' + params : ''))
        .then(response => {
            return response.data.data
        });
}