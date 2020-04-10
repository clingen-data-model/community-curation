const queryStringFromParams = function (params) {
    let {filter, ...rest} = params;
    let parsedParams = {...filter, ...rest};
    console.log(filter);
    console.log(parsedParams);

    let queryStringParts = [
        'page=' + (parsedParams.currentPage ? parsedParams.currentPage : 1)
    ];
    delete (parsedParams.currentPage)
    for (let param in parsedParams) {
        if (parsedParams[param] === null || parsedParams[param] === undefined) {
            continue;
        }
        queryStringParts.push(encodeURIComponent(param) + '=' + encodeURIComponent(parsedParams[param]));
    }
   
    return queryStringParts.join('&');    
}

const getPageOfVolunteers = function (params) {
    const url = '/api/volunteers' + '?' + queryStringFromParams(params);
    return window.axios.get(url);
}

export default getPageOfVolunteers;