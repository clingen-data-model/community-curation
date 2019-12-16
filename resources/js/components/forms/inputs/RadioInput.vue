<style></style>

<template>
    <div class="form-check">
        <input 
            type="radio" 
            :name="name" 
            :value="radioValue" 
            :id="computedId" 
            :checked="value" 
            @change="$emit('input', radioValue)"
        >
        <label :for="computedId">
            <slot name="label"></slot>
        </label>
    </div>        
</template>

<script>
    export default {
        props: {
            name: {
                required: true,
            },
            id: {
                required: false
            },
            radioValue: {
                required: true
            },
            value: {
                requred: true
            }
        },
        data() {
            return {

            }
        },
        computed: {
            computedId: function () {
                return this.id ? this.id : this.name+'-'+this.value;
            }
        },
        methods: {}
    
}
</script>