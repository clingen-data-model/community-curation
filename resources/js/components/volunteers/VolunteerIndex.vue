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
            <a href="/assignments-report" class="float-right btn btn-sm btn-primary">Assignments Report</a>
            <h1>Volunteers</h1>
        </div>
        <div class="card-body">
            <div class="flex-row mb-2 p-0">
                <div class="form-inline">
                    <label for="filter-input">Search:</label>
                    &nbsp;
                    <input type="text" 
                        class="form-control form-control-sm" 
                        v-model="filters.searchTerm" 
                        placeholder="filter rows" 
                        id="filter-input"
                    >
                </div>
                <div class="border-left pl-3" id="type-filter-container">
                    <select id="type-select" 
                        class="form-control form-control-sm" 
                        v-model="filters.volunteer_type_id" 
                        @change="reconcileFilters"
                    >
                        <option :value="null">Any Type</option>
                        <option v-for="(type, idx) in volunteerTypes"
                            :key="idx"
                            :value="type.id">
                            {{type.name}}
                        </option>
                    </select>
                </div>
                <div id="status-filter-container">
                    <select id="status-select" 
                        class="form-control form-control-sm" 
                        v-model="filters.volunteer_status_id"
                    >
                        <option :value="null">Any Status</option>
                        <option v-for="(status, idx) in volunteerStatuses"
                            :key="idx"
                            :value="status.id">
                            {{status.name}}
                        </option>
                    </select>
                </div>
                <div id="curation-activity-filter-container">
                    <select id="activity-select" 
                        class="form-control form-control-sm" 
                        v-model="filters.curation_activity_id" 
                        :disabled="filters.volunteer_type_id == 1"
                    >
                        <option :value="null">Any Activity</option>
                        <option v-for="(activity, idx) in activities"
                            :key="idx"
                            :value="activity.id">
                            {{activity.name}}
                        </option>
                    </select>
                </div>
                <div id="expert-panel-filter-container">
                    <select id="panel-select" 
                        class="form-control form-control-sm" 
                        v-model="filters.expert_panel_id"
                        :disabled="filters.volunteer_type_id == 1"
                        style="max-width: 200px"
                    >
                        <option :value="null">Any Expert Panel</option>
                        <option v-for="(panel, idx) in filteredExpertPanels"
                            :key="idx"
                            :value="panel.id">
                            {{panel.name}}
                        </option>
                    </select>
                </div>
                <div>
                    <b-pagination
                        size="sm"
                        hide-goto-end-buttons
                        :total-rows="totalRows"
                        :per-page="pageLength"
                        v-model="currentPage"
                        class="border-left pl-3"
                    ></b-pagination>
                </div>
            </div>
            <div>
                <b-table 
                    :items="volunteerProvider" 
                    :fields="tableFields"
                    :sort-by.sync="sortKey"
                    :sort-desc.sync="sortDesc"
                    @sort-changed="handleSortChanged"
                    :no-local-sorting="true"
                    :show-empty="true"
                    :filter="filters"
                    :current-page="currentPage"
                    :busy.sync="loadingVolunteers"
                    @row-clicked="navigateToVolunteer"
                >
                    <template v-slot:cell(id)="{item}">
                        <a :href="'/volunteers/'+item.id">{{item.id}}</a>
                    </template>
                    <template v-slot:cell(name)="{item}">
                        <a :href="'/volunteers/'+item.id">{{item.name}}</a>
                    </template>
                    <template v-slot:cell(email)="{item}">
                        <a :href="'/volunteers/'+item.id">{{item.email}}</a>
                    </template>
                    <template v-slot:cell(assignments)="{item}">
                        <assignment-brief-list 
                            :assignments="item.assignments"
                            v-if="item && item.assignments.length > 0"
                        ></assignment-brief-list>
                    </template>
                </b-table>
            </div>
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
    import getPageOfVolunteers from '../../resources/volunteers/get_page_of_volunteers'
    import getAllCurationActivitys from '../../resources/curation_activities/get_all_curation_activities'
    import getAllExpertPanels from '../../resources/expert_panels/get_all_expert_panels'
    import getAllCurationActivities from '../../resources/curation_activities/get_all_curation_activities';
    import getAllVolunteerStatuses from '../../resources/volunteers/get_all_volunteer_statuses';
    import getAllVolunteerTypes from '../../resources/volunteers/get_all_volunteer_types';

    import AssignmentBriefList from './../assignments/AssignmentBriefList'

    import { randomBytes } from 'crypto';

    export default {
        components: {
            AssignmentBriefList
        },
        data() {
            return {
                volunteers: [],
                loadingVolunteers: false,
                showAssignmentModal: false,
                currentVolunteer: null,
                tableFields: [
                    {
                        key: 'id',
                        label: 'ID',
                        sortable: true,
                    },
                    {
                        key: 'first_name',
                        label: 'First',
                        sortable: true,
                        key: 'first_name',
                    },
                    {
                        key: 'last_name',
                        label: 'Last',
                        sortable: true,
                        key: 'last_name',
                    },
                    {
                        key: 'email',
                        label: 'Email',
                        sortable: true,
                        key: 'email'
                    },
                    {
                        key: 'type',
                        label: 'Type',
                        sortable: false,
                        key: 'volunteer_type.name'
                    },
                    {
                        label: 'Status',
                        sortable: false,
                        key: 'volunteer_status.name'
                    },
                    {
                        key: 'assignments',
                        sortable: false
                    }
                ],
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
                },
                totalRows: 0,
                pageLength: 25,
                currentPage: 1,
                sortKey: null,
                sortDesc: false,
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
            filteredExpertPanels: function () {
                if (!this.panels) {
                    return [];
                }
                if (this.filters.curation_activity_id) {
                    return this.panels.filter(panel => panel.curation_activity_id == this.filters.curation_activity_id)
                }
                return this.panels
            }
        },
        watch: {
            filter: function (to, from) {
                if (to != from) {
                    this.resetCurrentPage();
                }
            }
        },
        methods: {
            reconcileFilters() {
                if (this.filters.volunteer_type_id == 1) {
                    this.filters.curation_activity_id = null;
                    this.filters.expert_panel_id = null;
                }
            },
            volunteerProvider (context, callback) {
                console.log('volunteerProvider')
                // this.loadingVolunteers = true;
                getPageOfVolunteers(context)
                    .then(response => {
                        this.totalRows = response.data.meta.total;
                        callback(response.data.data);
                    });
                // this.loadingVolunteers = false;
            },
            handleSortChanged() {
                this.resetCurrentPage();
            },
            handleFiltered() {
                this.resetCurrentPage();
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
            resetCurrentPage() {
                this.currentPage = 1
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
            navigateToVolunteer(volunteer) {
                console.log("NavigateToVolunteer");
                window.location = '/volunteers/'+volunteer.id
            }
        },
        mounted() {
            // this.getVolunteers();
            this.getCurationActivities()
            this.getExpertPanels()
            this.getStatuses()
            this.getTypes()
        }
    
}
</script>