let Volunteer = class {
    constructor(data = {})
    {
        let defaults = {
            name: null,
            id: null,
            volunteer_type: {
                id: null,
                name: '',
            },
            volunteer_status: {
                id: null,
                name: ''
            },
            priorities: [],
            latest_priorities: [
                {
                    
                }
            ],
            assignments: []
        };

        this.attributes = {...defaults, ...data};

        for (const key in this.attributes) {
            if (this.attributes.hasOwnProperty(key)) {
                this[key] = data[key];                
            }
        }
    }

    isLoaded()
    {
        const loaded = this.attributes !== null && typeof this.attributes !== 'undefined';
        return loaded;
    }

    isBaseline() 
    {
        if (this.isLoaded()) {
            return this.attributes.volunteer_type_id == 1;
        }
    }

    isComprehensive() 
    {
        if (this.isLoaded()) {
            return this.attributes.volunteer_type_id == 2;
        }
    }

    getAssignedActivities()
    {
        if (this.isLoaded) {
            return this.attributes.assignments.map(assignment => assignment.assignable);
        }
        return [];
    }

    assignedToBaseline()
    {
        return this.getAssignedActivities().map(ca => ca.name).includes('Baseline');
    }
}

export default Volunteer;