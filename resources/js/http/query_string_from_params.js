const queryStringFromParams = function (params = {}) {
    let parsedParams = params;
    if (Object.keys(params).includes('filter')) {
        let {filter, ...rest} = params;
        parsedParams = {...filter, ...rest};
    }

    let queryStringParts = [
        'page=' + (parsedParams.currentPage ? parsedParams.currentPage : 1)
    ];
    delete (parsedParams.currentPage)
    
    for (let param in parsedParams) {
        if (parsedParams[param] === null || parsedParams[param] === undefined) {
            continue;
        }

        if (Array.isArray(parsedParams[param]) ) {
            parsedParams[param].forEach(val => {
                queryStringParts.push(encodeURIComponent(param) + '[]=' + encodeURIComponent(parsedParams[param]));
            })
        } else {
            queryStringParts.push(encodeURIComponent(param) + '=' + encodeURIComponent(parsedParams[param]));
        }

    }
   
    return queryStringParts.join('&');    
}

export default queryStringFromParams;