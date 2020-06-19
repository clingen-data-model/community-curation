<template>
    <b-input-group :append="currentTimezone">
        <b-form-datepicker 
            v-model="dateValue" 
            @change="handleChange"
            :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
        ></b-form-datepicker>
        <b-form-timepicker v-model="timeValue" @change="handleChange"></b-form-timepicker>
    </b-input-group>
</template>
<script>
import moment from 'moment'

export default {
    props: {
        value: {
            required: true,
        }
    },
    data() {
        return {
            dateValue: null,
            timeValue: null
        }
    },
    computed: {
        currentTimezone() {
            return `${moment().tz(Intl.DateTimeFormat().resolvedOptions().timeZone).format('z')} (${Intl.DateTimeFormat().resolvedOptions().timeZone})`
        }
    },
    watch: {
        value: {
            immediate: true,
            handler: function (to, from) { 
                this.syncDateAndTime() 
            }
        },
        dateValue() {
            this.handleChange();
        },
        timeValue() {
            this.handleChange();
        }
    },
    methods: {
        handleChange() {
            let newDateTime = moment(this.dateValue + ' ' +this.timeValue, 'YYYY-MM-DD HH:mm:ss A');
            this.$emit('input', newDateTime);
            this.$emit('change');
        },
        syncDateAndTime() {
            this.dateValue = moment(this.value).format('YYYY-MM-DD');
            this.timeValue = moment(this.value).format('HH:mm');
        }
    },
}
</script>