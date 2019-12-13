<style></style>

<template>
    <div class="component-container">
        <div v-testing="testing">
            testing
        </div>
        <!-- <div v-is-volunteer>
            i am a volunteer
        </div>
        <div v-not-volunteer>
            I'm not a volunteer
        </div> -->
        <div class="card loading text-center" v-if="loading && !volunteer.name">
            <div class="card-header">Loading volunteer...</div>
        </div>
        <div class="card card-default" v-if="!loading || volunteer.name">
            <div class="card-header">
                <non-volunteer>
                    <b-dropdown id="user-menu-dropdown" text="..." variant="light" no-caret class="float-right" right>
                        <b-dropdown-item @click="showStatusForm = true">Update Status</b-dropdown-item>
                        <b-dropdown-item @click="showAssignmentForm = true">Update Assignments</b-dropdown-item>
                    </b-dropdown>
                </non-volunteer>
                <h3 class="mb-0">Volunteer - {{volunteer.name || 'loading...'}} <small>({{volunteer.id}})</small></h3>
            </div>
            <div class="card-body">
                <non-volunteer>
                    <volunteer-status-alert 
                        :volunteer="volunteer"
                        @updatestatus="showStatusForm = true"
                        @updatevolunteer="findVolunteer"
                    ></volunteer-status-alert>
                </non-volunteer>
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
            <status-form
                :volunteer="volunteer"
                @updatevolunteer="reloadVolunteer"
            ></status-form>
        </b-modal>
    </div>
</template>

<script>
    import volunteerSummary from './partials/VolunteerSummary'
    import volunteerStatusAlert from './partials/VolunteerStatusAlert'
    import ApplicationData from './partials/ApplicationData'
    import PrioritiesList from './partials/PrioritiesList'
    import StatusForm from './partials/StatusForm'
    import findVolunteer from '../../resources/volunteers/find_volunteer'
    import Volunteer from '../../entities/volunteer'

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
            PrioritiesList,
            StatusForm,
        },
        data() {
            return {
                testing: true,
                loading: false,
                volunteer: new Volunteer(),
                application: {},
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
            },
            fetchVolunteerStatuses: async function () {
                this.volunteerStatuses = await getAllVolunteerStatuses();
            },
            closeStatusWindow() {
                this.showStatusForm = false;
            },
            reloadVolunteer() {
                this.closeStatusWindow();
                this.findVolunteer();
            }
        },
        created() {
            this.findVolunteer()
        }
    
}
</script>