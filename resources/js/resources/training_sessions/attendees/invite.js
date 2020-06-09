const inviteAttendees = function (trainingSession, attendees) {
    return window.axios.post('/api/training-sessions/'+trainingSession.id+'/attendees', { 'attendee_ids': attendees.map(item => item.id)});
}

export default inviteAttendees;