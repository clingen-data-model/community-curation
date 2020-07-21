<template>
    <b-card>
        <template v-slot:header>
            <div class="d-flex justify-content-between">
                <h3 class="mb-0">
                    <a href="/curation-groups">Curation Groups</a> 
                    - {{curationGroup.name}}
                </h3>
                <div>
                    <a 
                        :href="`/admin/curation-group/${curationGroup.id}/edit`" 
                        class="btn btn-primary btn-sm" 
                        target="admin"
                    >
                        Edit
                    </a>
                </div>
            </div>
        </template>
        <div>
            <section>
                <header>
                    <h4>General Info</h4>
                </header>
                <dl class="row mb-0">
                    <dt class="col-md-4">Curation activity</dt>
                    <dd class="col-md-8">{{curationGroup.curation_activity.name}}</dd>
                    
                    <dt class="col-md-4">Working group</dt>
                    <dd class="col-md-8">{{curationGroup.working_group.name}}</dd>
                    
                    <dt class="col-md-4">Accepting Vollunteers</dt>
                    <dd class="col-md-8">{{curationGroup.accepting_volunteers ? 'Yes' : 'No'}}</dd>

                    <dt class="col-md-4">Url</dt>
                    <dd class="col-md-8">{{curationGroup.url ? curationGroup.url : '--' }}</dd>
                </dl>
            </section>
            
            <section>
                <header>
                    <h4>{{curationGroup.assignments.length}} Volunteers</h4>
                </header>

                <div class="d-flex flex-wrap chart-container">
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
                <b-table 
                    :fields="volunteerFields" 
                    :items="curationGroup.assignments"
                    @row-clicked="navigateToVolunteer"
                >
                </b-table>
            </section>
        </div>
    </b-card>
</template>

<script>
import PieChart from '../charts/PieChart';

export default {
    components: {
        PieChart
    },
    props: {
      initialGroup: {
          type: Object,
          default() {
              return {
                  name: 'loading...',
                  curation_activity: {
                      id: 0,
                      name: 'loading...'
                  },
                  working_group: {
                      id: 0,
                      name: 'loading...'
                  },
                  accepting_volunteers: 1,
                  assignments: [],
              }
          }
      }
    },
    data() {
        return {
            curationGroup: this.initialGroup,
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
                    key: 'status.name',
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

            ]
        }
    },
    computed: {
        volunteers () {
            return this.curationGroup.assignments.map(assignment => assignment.volunteer);
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
            this.curationGroup.assignments
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

<style scoped>
    section {
        margin-bottom: 1rem;
        border-bottom: solid 1px #eee;
        padding-bottom: .5rem;
    }

    .small { 
        max-width: 600px;
    }
    .chart-container > div {
        margin-right: 1rem;
        margin-bottom: 1rem;
        /* border-right: 1px solid #f0f; */
        width: 23%;
    }
</style>