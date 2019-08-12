<style></style>

<template>
    <div class="component-container">
        <h1>Volunteers</h1>
        <b-table :items="volunteers" :fields="tableFields">
            <template slot="id" slot-scope="{item}">
                <a :href="'/volunteers/'+item.id">{{item.id}}</a>
            </template>
            <template slot="name" slot-scope="{item}">
                <a :href="'/volunteers/'+item.id">{{item.name}}</a>
            </template>
            <template slot="email" slot-scope="{item}">
                <a :href="'/volunteers/'+item.id">{{item.email}}</a>
            </template>
        </b-table>
    </div>
</template>

<script>
    // import getAllVolunteers from '../../resources/volunteers/get_all_volunteers'
    export default {
        components: {},
        data() {
            return {
                tableFields: {
                    id: {
                        label: 'ID',
                        sortable: true,
                    },
                    name: {
                        label: 'Name',
                        sortable: true,
                        key: 'name',
                    },
                    email: {
                        label: 'Email',
                        sortable: true,
                        key: 'email'
                    },
                    type: {
                        label: 'Type',
                        sortable: true,
                        key: 'volunteer_type.name'
                    },
                    status: {
                        label: 'Status',
                        sortable: true,
                        key: 'volunteer_status.name'
                    },
                    assignments: {
                        sortable: true
                    }
                },
                volunteers: []
            }
        },
        methods: {
            getVolunteers() {
                // getAllVolunteers().then(data => this.volunteers = data);
                return window.axios.get('/api/volunteers')
                    .then(response => this.volunteers = response.data.data)
                    .catch(error => console.log(error));
            }
        },
        mounted() {
            this.getVolunteers();
        }
    
}
</script>