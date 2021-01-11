<template>
    <div v-if="hasPermission">
        <slot></slot>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'

export default {
    props: {
        permission: {
            required: true
        },
        all: {
            type: Boolean,
            required: false,
            default: false
        },

    },
    computed: {
        ...mapGetters({
            user: 'getUser'
        }),
        
        hasPermission(){
            if (typeof this.permission == 'string') {
                return this.user.hasPermission(this.permission)
            }

            if (typeof this.permission == 'array') {
                if (this.all) {
                    return hasAllPermissions()
                }
                return hasAnyPermission()
            }

            throw new Error('permission property of UserCan component must be string or array.');
        },
    },
    methods: {
        hasAnyPermission() {
            for (const perm in this.permission) {
                if (this.user.hasPermission(perm)) {
                    return true;
                }
            }
            return false;
        },

        hasAllPermissions() {
            for (const perm in this.permission) {
                if (!this.user.hasPermission(perm)) {
                    return false;
                }
            }
            return true;
        }
    }
}
</script>