<style></style>

<template>
    <div class="card">
        <div class="card-header">
            <h1>Volunteers</h1>
        </div>
        <div class="card-body">
            <div class="w-25 mb-2">
                <input type="text" class="form-control" v-model="searchTerm" placeholder="filter rows">
            </div>
            <b-table :items="filteredVolunteers" :fields="tableFields">
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
    </div>
</template>

<script>
    // import getAllVolunteers from '../../resources/volunteers/get_all_volunteers'
    export default {
        components: {},
        data() {
            return {
                searchTerm: null,
                volunteers: [],
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
            }
        },
        computed: {
            filteredVolunteers: function () {
                if (this.searchTerm === null) {
                    return this.volunteers;
                }
                return this.volunteers.filter(vol => {
                    return vol.name.toLowerCase().includes(this.searchTerm.toLowerCase())
                        || vol.email.toLowerCase().includes(this.searchTerm.toLowerCase())
                        || vol.volunteer_status.name.toLowerCase().includes(this.searchTerm.toLowerCase())
                        || vol.volunteer_type.name.toLowerCase().includes(this.searchTerm.toLowerCase());
                });
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