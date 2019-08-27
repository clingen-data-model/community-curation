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
                <template slot="assignments" slot-scope="{item}">
                    <div v-if="item && item.volunteer_type_id != 1">
                        <div v-if="item && item.assignments.length > 0">
                            <button class="btn btn-sm btn-default border float-right" @click="addAssignmentsToVolunteer(item)">Edit</button>
                            <ul class="list-unstyled">
                                <li v-for="(ass, idx) in item.assignments" :key="idx">
                                    {{ass.curationActivity.assignable.name}}
                                    <span v-if="ass.expertPanels.length > 0">
                                        -
                                        <span>{{ass.expertPanels.map(p => p.assignable.name).join(", ")}}</span>
                                    </span>
                                    <span v-else-if="ass.needsAptitude" class="text-muted">Needs aptitude</span>
                                    <span v-else class="text-muted">
                                        - None
                                    </span>                            
                                </li>
                            </ul>
                        </div>
                        <button 
                            class="btn btn-sm btn-default border"
                            @click="addAssignmentsToVolunteer(item)" 
                            v-else
                        >
                            Assign
                        </button>
                    </div>
                </template>
            </b-table>
        </div>
        <b-modal v-model="showAssignmentModal" hide-header hide-footer>
            <assignment-form 
                :volunteer="currentVolunteer" 
                @saved="updateVolunteers">
            </assignment-form>
        </b-modal>
    </div>
</template>

<script>
    import getAllVolunteers from '../../resources/volunteers/get_all_volunteers'
    export default {
        components: {},
        data() {
            return {
                searchTerm: null,
                volunteers: [],
                showAssignmentModal: false,
                currentVolunteer: null,
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
            getVolunteers: async function () {
                // this.volunteers = await getAllVolunteers().then(response => console.log(response));
                // getAllVolunteers().then(data => this.volunteers = data);
                return window.axios.get('/api/volunteers')
                    .then(response => {
                        this.volunteers = response.data.data
                        return response
                    })
                    .catch(error => console.log(error));
            },
            updateVolunteers() {
                this.getVolunteers()
                    .then(response => {
                        console.log('updating volunteers');
                        if (currentVolunteer !== null) {
                            currentVolunteer = this.volunteers.find(v => v.id === currentVolunteer.id)
                        }
                    })
            },
            addAssignmentsToVolunteer(volunteer) {
                this.currentVolunteer = volunteer;
                this.showAssignmentModal = true;
            }            
        },
        mounted() {
            this.getVolunteers();
        }
    
}
</script>