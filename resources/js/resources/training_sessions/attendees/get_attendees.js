const getAttendees = async function (trainingSession) {
    const attendees = await window.axios.get('/api/training-sessions/'+trainingSession.id+'/attendees')
                        .then(response => response.data.data)
                        .catch(error => {
                            if (error.response) {
                                alert(error.response.statusText);
                            } else {
                                alert('There was a problem getting attendees for training session ')
                            }
                        });
    return attendees;
}

export default getAttendees;