

<template>
    <div v-if="user.isOnlyVolunteer()">
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
