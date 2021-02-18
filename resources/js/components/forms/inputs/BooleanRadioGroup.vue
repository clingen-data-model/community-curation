<template>
    <radio-group
        :name="name"
        :options="options"
        v-model="proxyValue"
    ></radio-group>
</template>
<script>
export default {
    props: {
        name: {
            type: String,
            required: false,
            default: null
        },
        options: {
            type: Array,
            required: false,
            default: () => {
                return [{label: 'Yes', value: 1}, {label: 'No', value: 0}]
            }
        },
        value: {
            required: true
        }
    },
    computed: {
        proxyValue: {
            immediate: true,
            get() {
                return this.value
            },
            set(newValue) {
                this.$emit('input', newValue)
            }
        }
    }
}
</script>