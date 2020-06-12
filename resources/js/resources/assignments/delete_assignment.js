const deleteAssignment = function (assignment) {
    return window.axios.delete('/api/assignments/'+assignment.id);
}

export default deleteAssignment;