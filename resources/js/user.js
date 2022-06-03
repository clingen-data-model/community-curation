import { indexOf } from "lodash";
import moment from 'moment-timezone'

const dates = [
    'created_at',
    'updated_at',
    'deleted_at',
    'last_logged_in_at',
    'last_logged_out_at'
];

let User = class {

    constructor(data) {
        this.attributes = data;
        for (const key in data) {
            if (data.hasOwnProperty(key)) {
                if (dates.indexOf(key) > -1 && data[key] !== null) {
                    this[key] = moment(data[key]);
                    continue;
                }
                this[key] = data[key];
            }
        }
    }

    isLoaded() {
        const loaded = this.attributes !== null && typeof this.attributes !== 'undefined';
        return loaded;
    }

    hasRole(roleName) {
        if (this.isLoaded()) {
            return this.roles.filter(role => role.name == roleName).length > 0;
        }
        return false
    }

    hasPermission(permissionName) {
        if (this.isLoaded()) {
            return this.permissions.filter(perm => perm.name == permissionName).length > 0;
        }
        return false
    }

    isVolunteer() {
        return this.hasRole('volunteer');
    }

    notVolunteer() {
        return !this.isVolunteer();
    }

    isAdmin() {
        return this.hasRole('admin') || this.hasRole('super-admin');
    }

    isAdminOrProgrammer() {
        return this.isAdmin() || this.isProgrammer();
    }

    isProgrammer() {
        return this.hasRole('programmer');
    }

    isBasicVolunteer() {
        return this.isVolunteer() && this.attributes.volunteer_type_id == 1;
    }

    isComprehensiveVolunteer() {
        return this.isVolunteer() && this.attributes.volunteer_type_id == 2;
    }

    getPreference(prefName) {
        if (this.isLoaded() && this.preferences) {
            const pref = this.preferences.find(pref => pref.name == prefName);
            if (pref) {
                return pref.value;
            }
        }
        return null;
    }

    isCurrentUser(user) {
        return user.id = this.attributes.id;
    }
}

export default User;