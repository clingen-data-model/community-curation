<template>
    <div>
        <b-input-group :append="currentTimezone">
            <date-picker class="form-control" v-model="dateValue"></date-picker>
            <time-picker class="form-control" v-model="timeValue"></time-picker>
        </b-input-group>
    </div>
</template>
<script>
import moment from 'moment'
import TimePicker from './forms/inputs/TimePicker'
import DatePicker from './forms/inputs/DatePicker'

export default {
    components: {
        TimePicker,
        DatePicker
    },
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
            return `${moment().tz(Intl.DateTimeFormat().resolvedOptions().timeZone).format('z')}`
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
            console.info('handleChange timeValue:', this.timeValue)
            console.info('handleChange', newDateTime)
            this.$emit('input', newDateTime);
            this.$emit('change');
        },
        syncDateAndTime() {
            this.dateValue = moment(this.value).format('YYYY-MM-DD');
            this.timeValue = moment(this.value).format('HH:mm:ss');
        }
    },
}
</script>