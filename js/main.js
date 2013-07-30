/*
    PAYMILL UNITE
*/

function redirectOauth(paymill_root, client_id, params, redirect_uri) {
    redirect_uri = redirect_uri ? '&redirect_uri=' + redirect_uri : '';

    var url = encodeURI(paymill_root + '/authorize?client_id=' + client_id + '&scope=' + params + '&response_type=code' + redirect_uri);

    document.location.href = url;
}