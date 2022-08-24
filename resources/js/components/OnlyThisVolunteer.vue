
<script>
    import { mapGetters } from 'vuex';

    export default {
        props: {
            volunteer: {
                type: Object,
                required: true
            }
        },
        computed: {
            ...mapGetters({
                user: 'getUser'
            })
        }
}
</script>

<template>
    <div v-if="user.isThisVolunteer(volunteer)">
        <slot></slot>
    </div>
</template>
