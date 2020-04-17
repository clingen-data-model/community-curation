class AptitudeCollection {
    constructor(items = []) {
        this.items = items;
    }

    [Symbol.iterator]() { return this.items.values() }

    get length() {
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
        return new AptitudeCollection(this.items.filter(callback));
    }

    map(callback) {
        return new AptitudeCollection(this.items.map(callback));
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
        return this.filter(apt => apt.is_primary)
    }
    secondary() {
        return this.filter(apt => !apt.is_primary)
    }
}

export default AptitudeCollection;