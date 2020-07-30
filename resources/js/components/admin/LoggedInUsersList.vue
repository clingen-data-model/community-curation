<template>
    <div>
        <b-table :fields="fields" :items="usersProvider"></b-table>
    </div>
</template>
<script>
import getUsers from '../../resources/users/get_users'
import { BTable } from 'bootstrap-vue'

export default {
    components: {
        BTable
    },
    data() {
        return {
            fields: [
                {
                    key: 'first_name',
                    sortable: true,
                    label: 'First'
                },
                {
                    key: 'last_name',
                    sortable: true,
                    label: 'Last'
                },
                {
                    key: 'email',
                    sortable: true,
                    label: 'Email'
                },
                {
                    key: 'roles.name',
                    sortable: true,
                    label: 'Role'
                }
            ],
            totalRows: 0
        }
    },
    computed: {

    },
    methods: {
         usersProvider (context, callback) {
            //  console.log(context);
            context['is_logged_in'] = 1;
            getUsers(context)
                .then(response => {
                    callback(response.data.data)
                })
        }
    }
}
</script>