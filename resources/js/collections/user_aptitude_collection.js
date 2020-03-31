const UserAptitudeCollection = class {

    constructor(items = []) {
        this.items = items;
    }

    [Symbol.iterator]() { return this.items.values() }

    length() {
        return this.items.length;
    }

    push(item) {
        this.items.push(item);
    }

    shift(item) {
        this.items.shift(item);
    }

    unshift(item) {
        this.items.unshift(item);
    }

    filter(callback) {
        return new UserAptitudeCollection(this.items.filter(callback));
    }

    map(callback) {
        return new UserAptitudeCollection(this.items.map(callback));
    }

    includes(item) {
        return this.items.includes(item);
    } 

    all() {
        return this.items;
    }

    get(idx) {
        return this.items[idx];
    }

    put(idx, value) {
        return this.items[idx] = value;
    }

    primary() {
        return new UserAptitudeCollection(this.items.filter(userApt => userApt.aptitude.is_primary))
    }

    secondary() {
        return new UserAptitudeCollection(this.items.filter(userApt => !userApt.aptitude.is_primary))
    }
    
    granted() {
        return new UserAptitudeCollection(this.items.filter(userApt => userApt.granted_at != null))
    }
    
    trained() {
        return new UserAptitudeCollection(this.items.filter(userApt => userApt.trained_at != null))
    }
    
    untrained() {
        return new UserAptitudeCollection(this.items.filter(userApt => userApt.trained_at === null))
    }
    
    pending() {
        return new UserAptitudeCollection(this.items.filter(userApt => userApt.granted_at === null))
    }
}

export default UserAptitudeCollection;