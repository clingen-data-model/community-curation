<template>
    <span>
        <input type="date" 
            v-model="dateValue" 
            placeholder="YYYY-MM-DD" 
            @change="handleChange"
            class="form-control"
        >
        <input type="time" 
            v-model="timeValue" 
            placeholder="HH:mm am/pm" 
            @change="handleChange"
            class="form-control"
        >
    </span>
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
    watch: {
        value: {
            immediate: true,
            handler: function (to, from) { 
                this.syncDateAndTime() 
            }
        }
    },
    methods: {
        handleChange() {
            let newDateTime = moment(this.dateValue + ' ' +this.timeValue, 'YYYY-MM-DD HH:mm A');
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