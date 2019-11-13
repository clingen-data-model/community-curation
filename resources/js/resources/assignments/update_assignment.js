export default function (assignmentId, data) {
    return window.axios.put('/api/assignments/'+assignmentId, data)
}