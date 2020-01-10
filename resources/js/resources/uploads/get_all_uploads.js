export default async function (params) {
    const baseUrl = '/api/curator-uploads';
    
    return await axios.get(baseUrl+((params) ? '?'+params : ''))
                    .then(response => {
                        return response.data.data
                    });
}