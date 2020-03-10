const getAllGenes = async function (params) {
    const baseUrl = '/api/genes';
    
    return await axios.get(baseUrl+((params) ? '?'+params : ''))
        .then(response => response.data.data)
}

export default getAllGenes;