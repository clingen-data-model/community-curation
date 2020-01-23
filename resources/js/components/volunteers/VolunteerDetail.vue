<style></style>

<template>
    <div class="component-container">
        <div class="card loading text-center" v-if="loading && !volunteer.name">
            <div class="card-header">Loading volunteer...</div>
        </div>
        <div class="card card-default" v-if="!loading || volunteer.name">
            <div class="card-header">
                <non-volunteer>
                    <b-dropdown id="user-menu-dropdown" text="..." variant="light" no-caret class="float-right" right>
                        <b-dropdown-item @click="showStatusForm = true">Update Status</b-dropdown-item>
                        <b-dropdown-item @click="showAssignmentForm = true" v-if="volunteer.isComprehensive()">Update Assignments</b-dropdown-item>
                        <b-dropdown-item @click="showVolunteerTypeForm = true" v-if="!volunteer.isComprehensive()">Make Comprehensive</b-dropdown-item>
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
                    <b-tab title="Priorities" v-if="volunteer.isComprehensive()">
                        <priorities-list :volunteer="volunteer"></priorities-list>
                    </b-tab>
                    <b-tab title="Documents">
                        <documents-card :volunteer="volunteer"></documents-card>
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
        <b-modal title="Convert Volunteer to Comprehensive?" hide-footer v-model="showVolunteerTypeForm">
            <p>You are about to convert this volunteer from Baseline to Comprehensive.</p>  
            <p>If you confirm the volunteer will be notified and instructed prioritize Curation Activities and Expert Panels.</p>
            <button class="btn btn-default" @click="showVolunteerTypeForm = false">Cancel</button>
            <button class="btn btn-primary" @click="convertVolunteerToComprehensive">Convert</button>
        </b-modal>
    </div>
</template>

<script>
    import findVolunteer from '../../resources/volunteers/find_volunteer'
    import updateVolunteer from '../../resources/volunteers/update_volunteer'

    import volunteerSummary from './partials/VolunteerSummary'
    import volunteerStatusAlert from './partials/VolunteerStatusAlert'
    import ApplicationData from './partials/ApplicationData'
    import PrioritiesList from './partials/PrioritiesList'
    import StatusForm from './partials/StatusForm'
    import Volunteer from '../../entities/volunteer'
    import DocumentsCard from './partials/DocumentsCard'

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
            DocumentsCard
        },
        data() {
            return {
                testing: true,
                loading: false,
                volunteer: new Volunteer(),
                application: {},
                showStatusForm: false,
                showVolunteerTypeForm: false,
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
            },
            convertVolunteerToComprehensive() {
                updateVolunteer(this.volunteer.id, { volunteer_type_id: 2})
                    .then(() => this.findVolunteer())
                    .then(() => this.showVolunteerTypeForm = false);
            }
        },
        created() {
            this.findVolunteer()
        }
    
}
</script>