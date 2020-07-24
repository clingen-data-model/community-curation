<template>
    <div>
        <div class="d-flex flex-wrap chart-container border-bottom mb-4">
            <div>
                <div class="lead text-center">Assignment Status</div>
                <pie-chart 
                :chart-data="volunteersByStatus" 
                ></pie-chart>
            </div>
            <div>
                <div class="lead text-center">Timezones</div>
                <pie-chart 
                    :chart-data="volunteersByTimezone"
                ></pie-chart>
            </div>
            <div>
                <div class="lead text-center">Countries</div>
                <pie-chart 
                    :chart-data="volunteersByCountry"
                ></pie-chart>
            </div>
            <div>
                <div class="lead text-center">Self Description</div>
                <pie-chart 
                    :chart-data="volunteersByRole"
                ></pie-chart>
            </div>
        </div>
        <div>
            <div class="clearfix">
                <b-pagination
                    v-model="currentPage"
                    :total-rows="assignments.length"
                    :per-page="25"
                    v-if="assignments.length > 25"
                    hide-goto-end-buttons
                    class="float-right"
                ></b-pagination>
            </div>
            <b-table 
                :fields="tableFields" 
                :items="assignments"
                @row-clicked="navigateToVolunteer"
                :per-page="25"
                :current-page="currentPage"
            >
            </b-table>
            <div class="clearfix">
            <b-pagination
                v-model="currentPage"
                :total-rows="assignments.length"
                :per-page="25"
                v-if="assignments.length > 25"
                hide-goto-end-buttons
                class="float-right"
            ></b-pagination>
            </div>
        </div>
    </div>
</template>
<script>
import PieChart from './charts/PieChart';
export default {
    components: {
        PieChart,
    },
    props: {
        assignments: {
            required: true,
            type: Array
        }
    },
    data() {
        return {
            volunteerFields: [
                {
                    key: 'volunteer.id',
                    sortable: true,
                    label: 'ID'
                },
                {
                    key: 'volunteer.first_name',
                    sortable: true,
                    label: 'First'
                },                      
                {
                    key: 'volunteer.last_name',
                    sortable: true,
                    label: "Last"
                },
                {
                    key: this.assignmentStatusPath,
                    sortable: true,
                    label: 'Status'
                },
                {
                    key: 'created_at',
                    sortable: true,
                    label: 'Date Assigned',
                    formatter: (value, key, item) =>{
                        return this.$options.filters.formatDate(value, 'YYYY-MM-DD')
                    }
                },
                {
                    key: 'user_aptitude.trained_at',
                    sortable: true,
                    label: 'Date trained',
                    formatter: (value, key, item) =>{
                        return this.$options.filters.formatDate(value, 'YYYY-MM-DD')
                    }
                }

            ],
            currentPage: 1,
            additionalTableFields: []            
        }
    },
    computed: {
        tableFields () {
            return this.volunteerFields.concat(this.additionalTableFields);
        },
        volunteers () {
            return this.assignments.map(assignment => assignment.volunteer);
        },
        volunteersByStatus() {
            return this.countVolunteersBy(assignment => {
                return assignment.status ? assignment.status.name : 'Unknown'
            });
        },
        volunteersByTimezone() {
            return this.countVolunteersBy(assignment => assignment.volunteer.timezone);
        },
        volunteersByCountry() {
            return this.countVolunteersBy(assignment => {
                return assignment.volunteer.country ? assignment.volunteer.country.name : 'Unknown';
            })
        },
        volunteersByRole() {
            return this.countVolunteersBy(assignment => {
                return (assignment.volunteer.application && assignment.volunteer.application.self_description) ? assignment.volunteer.application.self_description.name : 'Unknown'; 
            });
        }
    },
    methods: {
        countVolunteersBy(getAssignmentValue) {
            let grouped = {};
            this.assignments
                .map(assignment => getAssignmentValue(assignment))
                .forEach(value => {
                if (!grouped[value]) {
                    grouped[value] = 0;
                }   
                grouped[value] += 1;
            });
            return grouped;
        },
        navigateToVolunteer (item, index, event) {
            window.location = `/volunteers/${item.volunteer.id}`;
        }

    }
}
</script>

<style>
    .chart-container > div {
        margin-right: 1rem;
        margin-bottom: 1rem;
        /* border-right: 1px solid #f0f; */
        width: 23%;
    }
</style>