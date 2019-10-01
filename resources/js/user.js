let User = class {

    constructor(data)
    {
        this.attributes = data;
        for (const key in data) {
            if (data.hasOwnProperty(key)) {
                this[key] = data[key];
                
            }
        }
    }

    isLoaded()
    {
        const loaded = this.attributes !== null && typeof this.attributes !== 'undefined';
        return loaded;
    }

    hasRole(roleName) {
        if (this.isLoaded()) {
            console.log(this.attributes);
            return this.roles.filter(role => role.name == roleName).length > 0;
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
        return this.hasRole('admin');
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
}

export default User;