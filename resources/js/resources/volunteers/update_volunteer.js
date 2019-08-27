export default function (volunteerId, data) {
    return window.axios.put('/api/volunteers/'+volunteerId, data)
}