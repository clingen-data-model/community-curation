<style></style>

<template>
    <div class="card card-default">
        <div class="card-header">
            <h2>Volunteer - {{volunteer.name}} <small>({{volunteer.id}})</small></h2>
        </div>
        <div class="card-body">
            <b-tabs content-class="p-3 border-left border-right border-bottom">
                <b-tab title="Summary" active>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card pt-3 px-3">
                                <h4>{{volunteer.volunteer_type.name | ucfirst}} Volunteer</h4>
                                <p class="text-muted" v-if="!hasAssignments">
                                    A ClinGen staff member will contact you shortly about your curation activity assignment.
                                </p>
                            </div>
                            <!-- <div class="card mt-4 pt-3 px-3">
                                <h4>Aptitudes &amp; Trainings</h4>
                            </div> -->
                        </div>

                        <div class="col-md-6">
                            <div class="card p-3">
                                <h4>Contact Information</h4>
                                <dl class="row">
                                    <dt class="col-sm-4">Email:</dt>
                                    <dd class="col-sm-8">
                                        <a :href="'mailto:'+volunteer.email">{{volunteer.email}}</a>
                                    </dd>

                                    <dt class="col-sm-4" v-if="volunteer.phone">Phone:</dt>
                                    <dd class="col-sm-8" v-if="volunteer.phone">{{volunteer.phone || '&nbsp;'}}</dd>

                                    <dt class="col-sm-4" v-if="volunteer.address">Address:</dt>
                                    <dd class="col-sm-8" v-if="volunteer.address">{{volunteer.address || '&nbsp;'}}</dd>
                                </dl>
                            </div>

                            <!-- <div class="card mt-4 pt-3 px-3">
                                <h4>Baseline Curations &amp; Annotations</h4>
                            </div> -->
                        </div>
                    </div>
                </b-tab>
                <b-tab title="Application Survey Data">
                    <b-table
                        :items="applicationData"
                        small
                        hover
                        header-variant="light"
                        v-if="applicationData.length > 0"
                    ></b-table>
                    <div v-else class="alert alert-danger my-4 col-10 mx-auto">
                        <h4><strong>This volunteer did not complete an application.</strong></h4>
                        Based on the expected workflow this is not possible, but there are a few ways it could have happened:
                        <ul>
                            <li>This is a <strong>test database</strong> and you are looking at a volunteer that was created for testing purposes w/o completing an applications survey.</li>
                            <li>An admin <strong>created</strong> a volunteer user <strong>using the admin panel</strong>.</li>
                            <li>Something mysterious is going on and you should contact an administrator.</li>
                        </ul>
                    </div>
                </b-tab>
                <!-- <b-tab title="Priorities" disabled></b-tab>
                <b-tab title="Documents" disabled></b-tab> -->
            </b-tabs>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            id: {
                type: Number,
                required: true
            }
        },
        components: {},
        data() {
            return {
                volunteer: {
                    name: null,
                    id: null,
                    volunteer_type: {
                        id: null,
                        name: ''
                    }
                },
                application: {}
            }
        },
        computed: {
            hasAssignments: function (){
                return this.volunteer.assignments && this.volunteer.assignments.length > 0
            },
            applicationData: function () {
                if (!this.volunteer.application) {
                    return [];
                }
                const variableNames = Object.keys(this.volunteer.application);
                let data = [];
                for (let index = 0; index < variableNames.length; index++) {
                    const variable = variableNames[index];
                    if (['respondent_type', 'respondent_id', 'survey_id', 'duration', 'last_page'].indexOf(variable) > -1) {
                        continue;
                    }

                    let value = this.volunteer.application[variable];

                    data.push({
                        'variable': variable,
                        'value': value
                    })
                }
                return data;
            }
        },
        methods: {
            findVolunteer() {
                return window.axios.get('/api/volunteers/'+this.id)
                    .then(response => this.volunteer = response.data.data)
                    .catch(error => console.log(error))
            },
            findApplicationResponse() {
                return window.axios.get('/api/application-response/')
            }
        },
        mounted() {
            this.findVolunteer()
                // .then(response => {
                //     this.findApplicationResponse();
                // });
        }
    
}
</script>