export default class extends Array {
    primary() {
        this.filter(apt => apt.is_primary)
    }
    secondary() {
        this.filter(apt => !apt.is_primary)
    }
}