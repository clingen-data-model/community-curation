export default function (userId) 
{
    sessionStorage.removeItem('user');
    sessionStorage.removeItem('impersonatable-users');
    window.location.href = '/impersonate/take/'+userId
}