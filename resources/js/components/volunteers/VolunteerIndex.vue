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
    .filter-row .active {
        box-shadow: 0 0 5px #6cb2eb;
        font-weight: 700;
    }
</style>

<template>
    <div class="card">
        <div class="card-header">
            <assignments-report-button 
                :filter="filters" 
                :sort-by="sortKey" 
                :sort-desc="sortDesc"
            ></assignments-report-button>
            <h1>Volunteers</h1>
        </div>
        <div class="card-body">
            <div class="flex-row mb-2 p-0 filter-row">
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
                        :class="{active: filters.volunteer_type_id}"
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
                        :class="{active: filters.volunteer_status_id}"
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
                        :class="{active: filters.curation_activity_id}"
                        :disabled="filters.volunteer_type_id == 1"
                    >
                        <option :value="null">Any Activity</option>
                        <option v-for="(activity, idx) in activities"
                            :key="idx"
                            :value="activity.id">
                            {{activity.name}}
                        </option>
                        <option :value="-1">Not Assigned to activity</option>
                    </select>
                </div>
                <div id="expert-panel-filter-container">
                    <select id="panel-select" 
                        class="form-control form-control-sm" 
                        v-model="filters.expert_panel_id"
                        :disabled="filters.volunteer_type_id == 1"
                        :class="{active: filters.expert_panel_id}"
                        style="max-width: 200px"
                    >
                        <option :value="null">Any Expert Panel</option>
                        <option :value="-1">Not assigned to expert panel</option>
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
                    ref="volunteersTable"
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
                        <button @click="addAssignmentsToVolunteer(item)" class="btn btn-light border btn-xs" v-if="item.assignments.length == 0 && filters.curation_activity_id == -1">Assign</button>
                    </template>
                </b-table>
            </div>
        </div>
        <b-modal v-model="showAssignmentModal" hide-header hide-footer>
            <assignment-form 
                :volunteer="currentVolunteer" 
                @saved="updateCurrentVolunteer"
                showVolunteer>
            </assignment-form>
        </b-modal>
    </div>
</template>

<script>
    import getAllVolunteers from '../../resources/volunteers/get_all_volunteers'
    import findVolunteer from '../../resources/volunteers/find_volunteer'
    import getPageOfVolunteers from '../../resources/volunteers/get_page_of_volunteers'
    import getAllCurationActivitys from '../../resources/curation_activities/get_all_curation_activities'
    import getAllExpertPanels from '../../resources/expert_panels/get_all_expert_panels'
    import getAllCurationActivities from '../../resources/curation_activities/get_all_curation_activities';
    import getAllVolunteerStatuses from '../../resources/volunteers/get_all_volunteer_statuses';
    import getAllVolunteerTypes from '../../resources/volunteers/get_all_volunteer_types';

    import AssignmentBriefList from './../assignments/AssignmentBriefList'
    import AssignmentsReportButton from '../reports/AssignmentsReportButton'

    import { randomBytes } from 'crypto';

    export default {
        components: {
            AssignmentBriefList,
            AssignmentsReportButton
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
            },
        },
        watch: {
            filters: {
                handler: function (to, from) {
                    localStorage.setItem('volunteers-table-filters', JSON.stringify(this.filters));
                },
                deep: true
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
            async updateCurrentVolunteer () {
                this.$refs.volunteersTable.refresh()
                this.currentVolunteer = await findVolunteer(this.currentVolunteer.id)
            },
            async addAssignmentsToVolunteer(volunteer) {
                this.currentVolunteer = await findVolunteer(volunteer.id);
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
            },
            syncFiltersFromLocalStorage () {
                const storedFilters = JSON.parse(localStorage.getItem('volunteers-table-filters'));
                if (storedFilters) {
                    this.filters = storedFilters;
                }
            }
        },
        mounted() {
            // this.getVolunteers();
            this.getCurationActivities()
            this.getExpertPanels()
            this.getStatuses()
            this.getTypes()
            this.syncFiltersFromLocalStorage();
        }
    
}
</script>