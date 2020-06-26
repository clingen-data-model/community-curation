import TrainingSession from './../../entities/training_session'

const find = async function (trainingSessionId) {
    return await window.axios.get('/api/training-sessions/'+trainingSessionId)
        .then(response => {
            let trainingSession = response.data.data
            trainingSession = new TrainingSession(trainingSession);
            return trainingSession;
        });
}

export default find;