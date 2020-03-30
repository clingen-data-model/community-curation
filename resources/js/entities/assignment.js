import AptitudeCollection from '../collections/aptitude_collection'
import UserAptitudeCollection from '../collections/user_aptitude_collection'

const Assignment = class {
    constructor(data = {}) {
        let defaults = {
            id: null,
            user_id: null,
            assignable_type: null,
            assignable_id: null,
            assignment_status_id: null,
            parent_id: null,
            status: {},
            assignable: {
                aptitudes: []
            },
            sub_assignments: [],
            user_aptitudes: []
        }

        this.attributes = {...defaults, ...data};

        for (const key in this.attributes) {
            if (this.attributes.hasOwnProperty(key)) {
                this[key] = this.hydrateAttribute(key, data[key]);
            }
        }

        console.log(this.user_aptitudes instanceof UserAptitudeCollection);
    }

    getUnassignedAptitudes() {
        return this.assignable.aptitudes.filter(apt => {
            return !this.user_aptitudes.map(userApt => userApt.aptitude_id).includes(apt.id);
        })
    }

    hydrateAttribute(key, value)
    {
        switch (key) {
            case 'sub_assignments':
                if (value == null) {
                    return value;
                }
                return value.map(asn => new Assignment(asn));                
            case 'user_aptitudes':
                let collection =  new UserAptitudeCollection();
                if (!value) {
                    return collection;
                }
                value.forEach(element => {
                    collection.push(element)
                });
                return collection
            case 'aptitudes':
                return new AptitudeCollection(value);
            default:
                break;
        }
        return value;
    }
}

export default Assignment;