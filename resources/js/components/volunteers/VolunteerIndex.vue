<style lang="scss">
    .flex-row {
        display: flex;
        flex-direction: row;
    }
    .flex-row > div {
        margin-left: 1rem;
        &:first-child {
            margin-left: 0;
        }
    }
</style>

<template>
    <div class="card">
        <div class="card-header">
            <h1>Volunteers</h1>
        </div>
        <div class="card-body">
            <div class="flex-row mb-2">
                <div class="form-inline">
                    <label for="filter-input">Search:</label>
                    &nbsp;
                    <input type="text" class="form-control form-control-sm" v-model="filters.searchTerm" placeholder="filter rows" id="filter-input">
                </div>
                <!-- <div class="border-left pl-3" id="type-filter-container">
                    <select id="type-select" class="form-control" v-model="filters.volunteer_type_id">
                        <option :value="null">Any Type</option>
                        <option v-for="(type, idx) in volunteerTypes"
                            :key="idx"
                            :value="type.id">
                            {{type.name}}
                        </option>
                    </select>
                </div> -->
                <div class="border-left pl-3" id="status-filter-container">
                    <select id="status-select" class="form-control" v-model="filters.volunteer_status_id">
                        <option :value="null">Any Status</option>
                        <option v-for="(status, idx) in volunteerStatuses"
                            :key="idx"
                            :value="status.id">
                            {{status.name}}
                        </option>
                    </select>
                </div>
                <!-- <div id="curation-activity-filter-container">
                    <select id="activity-select" class="form-control" v-model="filters.curation_activity_id">
                        <option :value="null">Any Activity</option>
                        <option v-for="(activity, idx) in activities"
                            :key="idx"
                            :value="activity.id">
                            {{activity.name}}
                        </option>
                    </select>
                </div> -->
                <!-- <div id="expert-panel-filter-container">
                    <select id="panel-select" class="form-control" v-model="filters.expert_panel_id">
                        <option :value="null">Any Expert Panel</option>
                        <option v-for="(panel, idx) in panels"
                            :key="idx"
                            :value="panel.id">
                            {{panel.name}}
                        </option>
                    </select>
                </div> -->
            </div>
            <div class="alert alert-info" v-if="filteredVolunteers.length == 0">
                <div v-if="Object.keys(activeFilters).length >0">
                    No volunteers matched your search
                </div>
                <div v-else>
                    There are no volunteers in the system.
                </div>
            </div>
            <b-table :items="filteredVolunteers" :fields="tableFields" v-else>
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
                    <div v-if="item && item.volunteer_type_id == 2">
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
                    <div v-else class="text-muted">
                        N/A
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
    import getAllCurationActivitys from '../../resources/curation_activities/get_all_curation_activities'
    import getAllExpertPanels from '../../resources/expert_panels/get_all_expert_panels'
    import getAllCurationActivities from '../../resources/curation_activities/get_all_curation_activities';
    import getAllVolunteerStatuses from '../../resources/volunteers/get_all_volunteer_statuses';
    import getAllVolunteerTypes from '../../resources/volunteers/get_all_volunteer_types';
import { randomBytes } from 'crypto';

    export default {
        components: {},
        data() {
            return {
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
                volunteerTypes: [],
                volunteerStatuses: [],
                activities: [],
                panels: [],
                filters: {
                    searchTerm: null,
                    volunteer_type_id: null,
                    volunteer_status_id: null,
                    curation_activity_id: null,
                    expert_panel_id: null
                }
            }
        },
        computed: {
            activeFilters: function () {
                return Object.keys(this.filters)
                    .filter(key => this.filters[key] !== null)
                    .reduce((obj, key) => {
                        obj[key] = this.filters[key];
                        return obj;
                    }, {})
            },
            filteredVolunteers: function () {
                if (Object.keys(this.activeFilters).length === 0) {
                    return this.volunteers;
                }
                return this.volunteers.filter(volunteer => {
                    for(let key in this.activeFilters) {
                        if (['volunteer_status_id', 'volunteer_type_id'].indexOf(key) > -1) {
                            if (this.activeFilters[key] != volunteer[key]) {
                                return false
                            }
                        }
                        if (key == 'curation_activity_id') {
                            let matchingAssignments = volunteer.assignments.filter(ass => {
                                return ass[key] == this.activeFilters[key]
                            });
                            if (matchingAssignments.length == 0) {
                                return false
                            }
                        }

                        if (key == 'expert_panel_id') {
                            if (volunteer.assignments.length == 0) {
                                return false;
                            }
                            let matchingAssignments = volunteer.assignments.filter(ass => {
                                if (ass.expertPanels.length == 0) {
                                    return false;
                                }
                                let assignedExpertPanelIds = ass.expertPanels.map(ep => ep.assignable_id);
                                console.log(assignedExpertPanelIds);
                                return assignedExpertPanelIds.filter(epid => epid == this.activeFilters[key]).length > 0;
                            });

                            console.log(matchingAssignments);


                            if (matchingAssignments.length == 0) {
                                return false
                            }
                        }
                        if (key == 'searchTerm') {
                            if (!volunteer.name.toLowerCase().includes(this.filters.searchTerm.toLowerCase())
                                || !volunteer.email.toLowerCase().includes(this.filters.searchTerm.toLowerCase())
                                || !volunteer.volunteer_status.name.toLowerCase().includes(this.filters.searchTerm.toLowerCase())
                                || !volunteer.volunteer_type.name.toLowerCase().includes(this.filters.searchTerm.toLowerCase())) {
                                    return false;
                                }
                        }
                        return true;
                    }
                })
            }
        },
        methods: {
            getVolunteers: async function () {
                this.volunteers = await getAllVolunteers();
            },
            updateVolunteers: async function() {
                await this.getVolunteers()
                if (this.currentVolunteer !== null) {
                    this.currentVolunteer = this.volunteers.find(v => v.id == this.currentVolunteer.id)
                }
            },
            addAssignmentsToVolunteer(volunteer) {
                this.currentVolunteer = volunteer;
                this.showAssignmentModal = true;
            },
            async getCurationActivities() {
                this.activities = await getAllCurationActivities()
            },
            async getExpertPanels() {
                this.panels = await getAllExpertPanels()
            },
            async getStatuses() {
                this.volunteerStatuses = await getAllVolunteerStatuses()
            },
            async getTypes() {
                this.volunteerTypes = await getAllVolunteerTypes()
            },
        },
        mounted() {
            this.getVolunteers();
            this.getCurationActivities()
            this.getExpertPanels()
            this.getStatuses()
            this.getTypes()
        }
    
}
</script>