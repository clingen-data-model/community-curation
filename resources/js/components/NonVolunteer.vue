<template>
    <div v-if="user.isAdmin()">
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
