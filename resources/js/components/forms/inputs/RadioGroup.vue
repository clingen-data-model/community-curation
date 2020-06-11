<style></style>

<template>
    <div class="radio-group">
        <div class="form-check"
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