<template>
    <input 
        type="date" 
        :value="formattedDate" 
        @input="handleDateInput"
        @change="$emit('change')"
        :disabled="disabled"
    >
</template>
<script>
export default {
    props: {
        value: {
            required: false,
            default: null
        },
        disabled: {
            required: false,
            default: false
        }
    },
    emits: [
        'input',
        'change'
    ],
    data() {
        return {
        }
    },
    computed: {
        formattedDate () {
            if (!this.value) {
                return null;
            }
            const fmtdt = this.formatDate(this.value)
            return fmtdt
        }
    },
    methods: {
        handleDateInput(event) {
            return this.accountForTimezone(event.target.value);
        },
        accountForTimezone(dateString) {
            if (dateString === null) {
                return dateString;
            }
            const date = new Date(Date.parse(dateString));
            const adjustedDate = new Date(date.getTime() + date.getTimezoneOffset()*60*1000);
            this.$emit('input', adjustedDate.toISOString())
        },
        formatDate(date) {
            if (date === null) {
                return null;
            }
            let d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2) 
                month = '0' + month;
            if (day.length < 2) 
                day = '0' + day;

            return [year, month, day].join('-');
        }
    },
    mounted() {
        // normalize date to be start of day and account for user timezone.
        this.accountForTimezone(this.formatDate(this.value))
    }
}
</script>