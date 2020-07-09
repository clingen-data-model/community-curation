export default function (userId) 
{
    window.clearSessionStorage();
    window.location.href = '/impersonate/take/'+userId
}