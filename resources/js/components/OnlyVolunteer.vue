<style></style>

<template>
    <div v-if="user.isVolunteer()">
        <slot></slot>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';

    export default {
        computed: {
            ...mapGetters({
                user: 'getUser'
            })
        }
}
</script>