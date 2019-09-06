<style></style>

<template>
    <div class="component-container">
        <div class="card card-default">
            <div class="card-header">
                <b-dropdown id="user-menu-dropdown" text="..." variant="light" no-caret class="float-right" right>
                    <b-dropdown-item @click="showStatusForm = true">Update Status</b-dropdown-item>
                    <b-dropdown-item @click="showAssignmentForm = true">Update Assignments</b-dropdown-item>
                </b-dropdown>
                <h3 class="mb-0">Volunteer - {{volunteer.name || 'loading...'}} <small>({{volunteer.id}})</small></h3>
            </div>
            <div class="card-body">
                <div class="alert alert-danger" v-if="hasDangerStatus">
                    <button class="float-right btn btn-light btn-sm" @click="showStatusForm = true">Update Status</button>
                    <strong>Status Notice:</strong>
                    This volunteer has been marked
                    <strong>{{volunteer.volunteer_status.name}}</strong>
                </div>
                <b-tabs content-class="p-3 border-left border-right border-bottom">
                    <b-tab title="Summary" active>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card p-3">
                                    <h4>{{(volunteer.volunteer_type.name || 'loading...') | ucfirst }} Volunteer</h4>
                                    <div v-if="volunteer.volunteer_type.name == 'baseline'">

                                    </div>
                                    <div v-else>
                                        <div v-if="!hasAssignments">
                                            <div class="text-muted" v-if="userIsVolunteer()">
                                                A ClinGen staff member will contact you shortly about your curation activity assignment.
                                            </div>
                                            <div v-else>
                                                <button 
                                                    class="btn btn-lg btn-primary"
                                                    @click="showAssignmentForm = true"
                                                >
                                                    Assign Curation Activity
                                                </button>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <table class="table table-sm" v-if="volunteer.assignments.length > 0">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 30%">Curation Activity</th>
                                                        <th>Expert Panel</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(assignment, idx) in volunteer.assignments" :key="idx">
                                                        <td>
                                                            {{assignment.curationActivity.assignable.name}}
                                                        </td>
                                                        <td>
                                                            <div v-if="assignment.needsAptitude" class="text-muted">
                                                                Needs Aptitude
                                                            </div>
                                                            <div v-else>{{assignment.expertPanels.map(epAss => epAss.assignable.name).join(", ")}}</div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <button 
                                                class="btn btn btn-default border btn-sm"
                                                @click="showAssignmentForm = true"
                                            >
                                                Edit
                                            </button>
                                        </div>
                                        <b-modal v-model="showAssignmentForm" hide-header hide-footer>
                                            <assignment-form :volunteer="volunteer" @saved="findVolunteer"></assignment-form>
                                        </b-modal>
                                    </div>
                                </div>
                                <!-- <div class="card mt-4 pt-3 px-3">
                                    <h4>Aptitudes &amp; Trainings</h4>
                                </div> -->
                            </div>

                            <div class="col-md-6">
                                <div class="card p-3 mb-3">
                                    <h4>Basic Information</h4>
                                    <dl class="row">
                                        <dt class="col-sm-4">Volunteer Satus:</dt>
                                        <dd class="col-sm-8">
                                            {{volunteer.volunteer_status.name || 'loading...'}}
                                            &nbsp;
                                            <button class="btn btn-sm btn-default border" @click="showStatusForm = true">update</button>
                                        </dd>
                                        <dt class="col-sm-4">Email:</dt>
                                        <dd class="col-sm-8">
                                            <a :href="'mailto:'+volunteer.email">{{volunteer.email || 'loading...'}}</a>
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
                    <b-tab title="Priorities" v-if="isComprehensive">
                        <div class="alert alert-danger my-4 col-10 mx-auto" v-if="!volunteer.application">
                            <h4><strong>This volunteer did not complete an application.</strong></h4>
                            Based on the expected workflow this is not possible, but there are a few ways it could have happened:
                            <ul>
                                <li>This is a <strong>test database</strong> and you are looking at a volunteer that was created for testing purposes w/o completing an applications survey.</li>
                                <li>An admin <strong>created</strong> a volunteer user <strong>using the admin panel</strong>.</li>
                                <li>Something mysterious is going on and you should contact an administrator.</li>
                            </ul>
                        </div>

                        <table class="table table-striped table-sm" v-else>
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Curation Activity</th>
                                    <th>Expert Panel</th>
                                    <th>Effort Experience</th>
                                    <th>Activity Experience</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(priority, idx) in volunteer.latest_priorities" :key="idx">
                                    <td>{{priority.priority_order}}</td>
                                    <td>{{priority.curation_activity.name}}</td>
                                    <td>{{priority.expert_panel.name}}</td>
                                    <td>
                                        {{priority.effort_experience | boolToHuman }}
                                        <span v-if="priority.effort_experience == 1">
                                            - {{priority.effort_experience_details}}
                                        </span>
                                    </td>
                                    <td>
                                        {{priority.activity_experience | boolToHuman }}
                                        <span v-if="priority.activity_experience == 1">
                                            - {{priority.activity_experience_details}}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </b-tab>
                </b-tabs>
            </div>
        </div>
        <b-modal title="Update Status" hide-footer v-model="showStatusForm">
            <div class="form-row">
                <label for="volunteer-status-select" class="col-md-3">Volunteer Status</label>
                <div class="col-md-6">
                    <select v-model="newStatus" class="form-control form-control-sm">
                        <option v-for="(status, idx) in volunteerStatuses" :value="status" :key="idx">{{status.name}}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary btn-sm" @click="updateVolunteerStatus">Update Status</button>
                </div>
            </div>
        </b-modal>
    </div>
</template>

<script>
    import getAllVolunteerStatuses from '../../resources/volunteers/get_all_volunteer_statuses'
    import updateVolunteer from '../../resources/volunteers/update_volunteer'

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
                    loading: true,
                    name: null,
                    id: null,
                    volunteer_type: {
                        id: null,
                        name: ''
                    },
                    volunteer_status: {
                        id: null,
                        name: ''
                    }
                },
                application: {},
                volunteerStatuses: [],
                showAssignmentForm: false,
                showStatusForm: false,
                newStatus: null
            }
        },
        computed: {
            hasDangerStatus: function () {
                return ['retired', 'unresponsive', 'declined'].indexOf(this.volunteer.volunteer_status.name.toLowerCase()) > -1
            },
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
            },
            isComprehensive: function () {
                return this.volunteer.volunteer_type_id == 2;
            }
        },
        methods: {
            findVolunteer() {
                this.loading = true;
                return window.axios.get('/api/volunteers/'+this.id)
                    .then(response => {
                        this.volunteer = response.data.data
                        this.loading = false;
                        this.newStatus = this.volunteer.volunteer_status
                    })
                    .catch(error => console.log(error))
                    .then(() => {
                        this.loading = false
                    });
            },
            findApplicationResponse() {
                return window.axios.get('/api/application-response/')
            },
            fetchVolunteerStatuses: async function () {
                this.volunteerStatuses = await getAllVolunteerStatuses();
            },
            userIsVolunteer() {
                return false;
            },
            updateVolunteerStatus() {
                let confirmationMessage = 'Are you sure you want to update the volunteer\'s status?';
                switch (this.newStatus.name) {
                    case this.volunteer.volunteer_status.name:
                        this.closeStatusWindow();
                        return;
                        break;
                    // case 'Retired':
                    //     confirmationMessage = 'You are about to retire this volunteer.  This will also retire all of their assignments.  Are you sure you want to continue?'
                    //     break;
                    default:
                        break;
                }
                if (confirm(confirmationMessage)) {
                    updateVolunteer(
                        this.volunteer.id, 
                        {
                            'volunteer_status_id': this.newStatus.id
                        }
                    ).then(response => {
                        this.findVolunteer().then(() => this.closeStatusWindow());
                    })
                }
            },
            closeStatusWindow() {
                this.showStatusForm = false;
            }
        },
        mounted() {
            this.findVolunteer()
            this.fetchVolunteerStatuses();
        }
    
}
</script>