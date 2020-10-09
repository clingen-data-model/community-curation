<template>
    <input type="time" class="form-control" :value="stringValue" @blur="emitInput">
</template>
<script>
import {debounce} from 'lodash'

export default {
    props: {
        value: {
            required: true
        }
    },
    data() {
        return {
        }
    },
    computed: {
        stringValue() {
            return this.convertToTimeString(this.value)
        }
    },
    methods: {
        emitInput (evt) {
            this.$emit('input', evt.target.value)
        },
        convertToTimeString(input) {
            if (typeof input == 'string') {
                return input
            }
            if (typeof input == 'object') {
                if (input instanceof Date) {
                    return `${input.getHour()}:${input.getMinue()} `
                }
                if (input instanceof moment) {
                    return input.format('HH:mm A')
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