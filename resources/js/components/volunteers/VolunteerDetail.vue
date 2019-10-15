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
                <volunteer-status-alert 
                    :volunteer="volunteer"
                    @updatestatus="showStatusForm = true"
                    @updatevolunteer="findVolunteer"
                    v-if="!$store.state.user.isVolunteer()"
                ></volunteer-status-alert>
                <b-tabs content-class="p-3 border-left border-right border-bottom">
                    <b-tab title="Summary" active>
                        <volunteer-summary
                            :volunteer="volunteer"
                            @updatestatus="showStatusForm = true"
                            @updatevolunteer="findVolunteer"
                        ></volunteer-summary>
                    </b-tab>
                    <b-tab title="Application Survey Data">
                        <application-data :volunteer="volunteer"></application-data>
                    </b-tab>
                    <b-tab title="Priorities">
                        <priorities-list :volunteer="volunteer"></priorities-list>
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
    import volunteerSummary from './partials/VolunteerSummary'
    import volunteerStatusAlert from './partials/VolunteerStatusAlert'
    import ApplicationData from './partials/ApplicationData'
    import PrioritiesList from './partials/PrioritiesList'
    import findVolunteer from '../../resources/volunteers/find_volunteer'

    export default {
        props: {
            id: {
                type: Number,
                required: true
            }
        },
        components: {
            volunteerSummary,
            volunteerStatusAlert,
            ApplicationData,
            PrioritiesList
        },
        data() {
            return {
                volunteer: {
                    loading: true,
                    name: null,
                    id: null,
                    volunteer_type: {
                        id: null,
                        name: '',
                    },
                    volunteer_status: {
                        id: null,
                        name: ''
                    },
                    priorities: []
                },
                application: {},
                volunteerStatuses: [],
                showStatusForm: false,
                newStatus: null
            }
        },
        computed: {
            isComprehensive: function () {
                return this.volunteer.volunteer_type_id == 2;
            }
        },
        methods: {
            findVolunteer: async function () {
                this.loading = true;
                this.volunteer = await findVolunteer(this.id);
                this.loading = false;
                this.newStatus = this.volunteer.volunteer_status
            },
            fetchVolunteerStatuses: async function () {
                this.volunteerStatuses = await getAllVolunteerStatuses();
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
            },
        },
        mounted() {
            this.findVolunteer()
            this.fetchVolunteerStatuses();
        }
    
}
</script>