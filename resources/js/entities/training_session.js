let TrainingSession = class {
    constructor(data = {})
    {
        let defaults = {
            id: null,
            topic: {},
            topic_id: null,
            topic_type: 'App\\CurationActivity',
            starts_at: null,
            ends_at: null,
            url: null,
            invite_message: '',
            notes: '',
            created_at: null,
            updated_at: null,
        };
        
        this.attributes = {...defaults, ...data};
        this.dateFields = ['starts_at', 'ends_at', 'created_at', 'updated_at'];
        this.appends = ['duration'];

        for (const key in this.attributes) {
            if (this.attributes.hasOwnProperty(key)) {
                // this.attributes[key] = this.hydrateAttribute(key, data[key]);                
                // Object.defineProperty(
                //     this, 
                //     key, 
                //     {
                //         get: function () {
                //             return this.attributes[key]
                //         },
                //         set: function (value) {
                //             this.attributes[key] = value
                //         }
                //     })

                this[key] = this.hydrateAttribute(key, data[key]);
        }
        }

    }

    get duration() {
        return this.ends_at.diff(this.starts_at, 'minutes');
    }

    set duration(value) {
        this.ends_at = this.starts_at.clone().add(value, 'minutes')
    }

    get started() {
        return this.starts_at.isBefore(moment());
    }
    
    get ended() {
        return this.ends_at.isBefore(moment());
    }

    clone () {
        return new TrainingSession(this.attributes);
    }

    getDuration() {
        return this.duration;
    }

    exportAttributes () {
        let appended = {};
        return {...this.attributes,...{duration: this.duration}}
    }

    hydrateAttribute(key, value)
    {
        if (this.dateFields.includes(key)) {
            let hydrated = window.moment();
            if (value !== null) {
                hydrated = window.moment(value);
            }
            return hydrated;
        }
        return value;
    }

}

export default TrainingSession;