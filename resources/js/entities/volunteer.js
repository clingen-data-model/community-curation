import Assignment from './assignment';

let Volunteer = class {
    constructor(data = {}) {
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
            latest_priorities: [{

            }],
            assignments: [],
            already_member_cgs: []
        };

        this.attributes = {...defaults, ...data };

        for (const key in this.attributes) {
            if (this.attributes.hasOwnProperty(key)) {
                this[key] = this.hydrateAttribute(key, data[key]);
            }
        }
    }

    isLoaded() {
        const loaded = (this.attributes !== null && typeof this.attributes !== 'undefined');
        return loaded;
    }

    isBaseline() {
        if (this.isLoaded()) {
            return this.attributes.volunteer_type_id == 1;
        }
        return false;
    }

    isComprehensive() {
        if (this.isLoaded()) {
            return this.attributes.volunteer_type_id == 2;
        }
        return false;
    }

    isClingenMember() {
        if (this.isLoaded()) {
            return this.already_clingen_member == 1;
        }
    }

    getAssignedActivities() {
        if (this.isLoaded) {
            return this.attributes.assignments.map(assignment => assignment.assignable);
        }
        return [];
    }

    assignedToBaseline() {
        return this.getAssignedActivities().map(ca => ca.name).includes('Baseline');
    }

    hydrateAttribute(key, value) {
        if (key == 'assignments' && value) {
            value = value.map(asn => new Assignment(asn));
        }
        return value;
    }

    hasApplication() {
        return typeof this.application != 'undefined' && this.application !== null;
    }

    hasDemographicInfo() {
        if (this.application.self_desc.rawValue == null) {
            return false;
        }
        if (this.application.highest_ed.rawValue == null) {
            return false;
        }
        if (this.application.race_ethnicity.rawValue == null || this.application.race_enthnicity == []) {
            return false;
        }

        return true;
    }

    clone() {
        return new Volunteer(JSON.parse(JSON.stringify(this.attributes)));
    }
}

export default Volunteer;