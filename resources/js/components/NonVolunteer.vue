<template>
    <div v-if="user.notVolunteer()">
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