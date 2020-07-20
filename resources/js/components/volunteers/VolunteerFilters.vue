<template>
    <div class="flex-row mb-2 p-0 filter-row">
        <slot name="before"></slot>
        <div class="form-inline border-right pr-3" v-if="!hideSearch">
            <label for="filter-input">Search:</label>
            &nbsp;
            <input type="text" 
                class="form-control form-control-sm" 
                v-model="filters.searchTerm" 
                placeholder="filter rows" 
                id="filter-input"
            >
        </div>
        <div class="pl-3" id="type-filter-container">
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
        <div id="curation-group-filter-container">
            <select id="panel-select" 
                class="form-control form-control-sm" 
                v-model="filters.curation_group_id"
                :disabled="filters.volunteer_type_id == 1"
                :class="{active: filters.curation_group_id}"
                style="max-width: 200px"
            >
                <option :value="null">Any Curation Group</option>
                <option :value="-1">Not assigned to curation group</option>
                <option v-for="(panel, idx) in filteredCurationGroups"
                    :key="idx"
                    :value="panel.id">
                    {{panel.name}}
                </option>
            </select>
        </div>
        <slot name="after"></slot>
    </div>
</template>
<script>
    import getAllCurationGroups from '../../resources/curation_groups/get_all_curation_groups'
    import getAllCurationActivities from '../../resources/curation_activities/get_all_curation_activities';
    import getAllVolunteerStatuses from '../../resources/volunteers/get_all_volunteer_statuses';
    import getAllVolunteerTypes from '../../resources/volunteers/get_all_volunteer_types';

export default {
        props: {
            hideSearch: {
                required: false,
                default: false
            }
        },
        data() {
            return {
                filters: {
                    searchTerm: null,
                    volunteer_type_id: null,
                    volunteer_status_id: null,
                    curation_activity_id: null,
                    curation_group_id: null
                },
                volunteerTypes: [],
                volunteerStatuses: [],
                activities: [],
                panels: [],
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
            filteredCurationGroups: function () {
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
                    this.$emit('filters-changed', this.filters)
                },
                deep: true
            }
        },
        methods: {
            reconcileFilters() {
                if (this.filters.volunteer_type_id == 1) {
                    this.filters.curation_activity_id = null;
                    this.filters.curation_group_id = null;
                }
            },
            async getCurationActivities() {
                this.activities = await getAllCurationActivities()
            },
            async getCurationGroups() {
                this.panels = await getAllCurationGroups()
            },
            async getStatuses() {
                this.volunteerStatuses = await getAllVolunteerStatuses()
            },
            async getTypes() {
                this.volunteerTypes = await getAllVolunteerTypes()
            },
            reset () {
                this.filters = {
                    searchTerm: null,
                    volunteer_type_id: null,
                    volunteer_status_id: null,
                    curation_activity_id: null,
                    curation_group_id: null
                };
            }
        },
        mounted() {
            this.getCurationActivities()
            this.getCurationGroups()
            this.getStatuses()
            this.getTypes()
        }
    }
</script>