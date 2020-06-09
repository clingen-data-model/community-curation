const deleteTrainingSession = function (id)
{
    return window.axios.delete('api/training-sessions/'+id);
}

export default deleteTrainingSession;