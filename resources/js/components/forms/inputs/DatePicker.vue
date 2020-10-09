<template>
    <input type="date" class="form-control" :value="stringValue" @blur="emitInput">
</template>
<script>
import moment from 'moment'

export default {
    props: {
        value: {
            required: true
        }
    },
    computed: {
        stringValue() {
            return this.convertToDateString(this.value)
        }
    },
    methods: {
        emitInput(evt) {
            console.log(evt.target.value)
            this.$emit('input', evt.target.value)
        },
        convertToDateString(input) {
            console.log(input)
            if (typeof input == 'string') {
                return input
            }
            if (typeof input == 'object') {
                if (input instanceof Date) {
                    return `${input.getFullYear()}-${input.getMonth()}-${input.getDate()}`
                }
                if (input instanceof moment) {
                    return input.format('YYYY-MM-DD')
                }
            }
            if (typeof input == 'number') {
                return this.convertToDateString(moment(input));
            }

            if (typeof input == 'undefined') {
                return input
            }

            throw Error(`Prop 'value' was of unknown type.`);
        }
    }
}
</script>