

<template>
    <div class="radio-group">
        <div class="form-check mr-3"
            :class="{'form-check-inline': inline}"
            v-for="(option, idx) in options" :key="idx"
        >
            <input 
                type="radio" 
                :name="name" 
                :value="option.value" 
                :id="`${name}-${option.value}`" 
                v-model="valueCopy"
                @change="$emit('change')"
            >
            &nbsp;
            <label :for="`${name}-${option.value}`">
                {{option.label}}
            </label>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                required: false
            },
            name: {
                required: true,
                type: String
            },
            options: {
                required: true,
                type: Array
            },
            inline: {
                required: false,
                type: Boolean,
                default: false,
            }
            
        },  
        data() {
            return {
                valueCopy: this.value
            }
        },
        watch: {
            valueCopy: function () {
                this.$emit('input', this.valueCopy)
            }
        },
        methods: {}
    
}
</script>