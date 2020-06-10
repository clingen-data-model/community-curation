const getTrainableVolunteers = async function (trainingSession) {
    const volunteers = await window.axios.get('/api/training-sessions/'+trainingSession.id+'/trainable-volunteers')
                        .then(response => response.data.data)
                        .catch(error => {
                            alert(error.response.statusText);
                        });
    return volunteers;

} 

export default getTrainableVolunteers;