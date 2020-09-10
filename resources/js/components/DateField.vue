<template>
    <input 
        ref="input"
        type="text" 
        class="vue-date-field" 
        :placeholder="placeholder"
        v-bind:value="formatted" 
        v-on:input="$event.target.value = value" />
</template>
<script>
    import moment from 'moment';
    import datepicker from 'bootstrap-datepicker';
    require('bootstrap-datepicker/dist/css/bootstrap-datepicker.css')

    export default {
        name: 'date-field',
        props: ['name', 'value', 'placeholder'],
        data: function(){
            return {
            }
        },
        computed: {
            formatted: function(){
                return (this.value) ? moment(this.value).format('MM/DD/YYYY') : null;
            },
        },
        mounted: function(){
            this.$nextTick(function(){
                jQuery(this.$el).datepicker()
                    .on('changeDate', function(evt){
                        jQuery(this.$el).trigger('input');
                        this.$emit('input', moment(evt.date, 'MM/DD/YYYY').toDate());
                    }.bind(this));
            }.bind(this));
        }
    };
</script>