<template>
    <div>
        <button class="btn btn-default btn-sm border" @click="showSelector = !showSelector">Impersonate a user</button>
        <b-modal 
            title="Impersonate another user" 
            v-model="showSelector"
            @ok="impersonateSelected"
        >
                <select name="impersonate_id" 
                    v-model="selectedUser"
                    class="form-control"
                >
                    <option value="">Select user...</option>
                    <option 
                        v-for="user in users" :key="user.id"
                        :value="user">{{user.name}}
                    </option>
                </select>
        </b-modal>
        <b-modal 
            v-model="showProgress" 
            hide-footer 
            hide-header 
            no-close-on-backdrop 
            no-close-on-escape 
            hide-header-close 
        >
            <h3 class="text-center">Impersonating {{ selectedUser.name }}.</h3>
            <p class="text-center"> 
                The page will reload in a moment...
            </p>
        </b-modal>
    </div>
</template>
<script>
import getImpersonatableUsers from '../resources/users/get_impersonable_users'
import impersonateUser from '../resources/users/impersonate_user'

export default {
    props: {
        
    },
    data() {
        return {
            users: [],
            selectedUser: {},
            showSelector: false,
            showProgress: false,
        }
    },
    computed: {

    },
    methods: {
        impersonateSelected() {
            this.$emit('impersonated');
            this.showProgress = true;
            impersonateUser(this.seletedUser.id)
            
        },
        async getImpersonatableUsers () {
            this.users = await getImpersonatableUsers();
        }
    },
    mounted() {
        this.getImpersonatableUsers();
    }
}
</script>