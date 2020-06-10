const defaultTopicType = 'App\\CurationActivity';

let TrainingSession = class {
    constructor(data = {}) {

        let defaults = {
            topic_type: defaultTopicType,
            topic_id: null,
            url: null,
            starts_at: null,
            ends_at: null,
            invite_message: null,
            notes: null
        };

        this.attributes = {...defaults, ...data};
    }

    setDuration(durationInMinutes) {
        
    }
}