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

                <div class="d-flex">
                    <pie-chart 
                        :chart-data="volunteersByStatus" 
                        style="width: 50%; height: 100%"
                        title="Volunteer assignment statuses"
                    ></pie-chart>
                    <pie-chart 
                        :chart-data="volunteersByTimezone" 
                        style="width: 50%; height: 100%"
                        title="Volunteer timezones"
                    ></pie-chart>
                    <pie-chart 
                        :chart-data="volunteersByCountry" 
                        style="width: 50%; height: 100%"
                        title="Volunteer countries"
                    ></pie-chart>
                </div>
            </section>

            <section>
                <header>
                    <h4>Volunteers</h4>
                </header>
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
                  assignments: []
              }
          }
      }
    },
    data() {
        return {
            curationGroup: this.initialGroup,
            testChartData: {
                a: 11,
                b: 7,
                c: 5
            },
            testChartData2: {
                a: 100,
                b: 25,
                c: 10
            }
        }
    },
    computed: {
        volunteersByStatus() {
            let byStatus = {};
            this.curationGroup.assignments.forEach(assignment => {
                if (!byStatus[assignment.status.name]) {
                    byStatus[assignment.status.name] = 0;
                }   
                byStatus[assignment.status.name] += 1;
            });
            return byStatus;
        },
        volunteersByTimezone() {
            return this.countVolunteersBy(assignment => assignment.volunteer.timezone);
        },
        volunteersByCountry() {
            return this.countVolunteersBy(assignment => {
                return assignment.volunteer.country ? assignment.volunteer.country.name : 'Unknown';
            })
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
</style>