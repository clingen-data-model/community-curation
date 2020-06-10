<style></style>

<template>
    <div class="alert">
        <strong><a class="float-right crsr-pointer ml-2" @click="$emit('cleared')">x</a></strong>
        <slot></slot>
    </div>
</template>

<script>
export default {
    props: {
        autoClose: {
            type: Boolean,
            default: true
        },
        duration: {
            type: Number,
            default: 3000
        }
    },
    data() {
        return {
            timer: null,
        }
    },
    methods: {
        setTimer() {
            this.timer = setTimeout(() => {
                this.$emit('cleared')
            }, this.duration)
        }  
    },
    mounted() {
        if (this.autoClose) {
            this.setTimer();
        }
    }
}
</script>