

<template>
    <div class="component-container">
        <div v-if="$store.state.user.isProgrammer() " class="p-2 border my-2">
            <div>
                <small class="text-muted">Dev tools</small>
            </div>
            <button class="btn btn-dark btn-sm" @click="findVolunteer">Reload volunteer</button>
        </div>

        <div class="card loading text-center" v-if="loading && !volunteer.name">
            <div class="card-header">Loading volunteer...</div>
        </div>
        <div class="card card-default" v-if="!loading || volunteer.name">
            <div class="card-header">
                <div v-if="user.isAdminOrProgrammer()">
                    <b-dropdown id="user-menu-dropdown" text="..." variant="light" no-caret class="float-right" right>
                        <b-dropdown-item @click="showStatusForm = true">Update Status</b-dropdown-item>
                        <b-dropdown-item @click="showVolunteerTypeForm = true" v-if="!volunteer.isComprehensive()">Make Comprehensive</b-dropdown-item>
                        <b-dropdown-item @click="impersonateVolunteer" v-if="$store.state.user.isProgrammer() || $store.state.user.isAdmin()">Impersonate this volunteer</b-dropdown-item>
                    </b-dropdown>
                </div>
                <h3 class="mb-0">Volunteer - {{volunteer.name || 'loading...'}} <small>({{volunteer.id}})</small></h3>
            </div>
            <div class="card-body">

                <only-this-volunteer :volunteer="volunteer">
                    <demographic-info :volunteer="volunteer" @updated="handleUpdate"></demographic-info>
                </only-this-volunteer>

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
                            @updatevolunteer="handleUpdate"
                        ></volunteer-summary>
                    </b-tab>
                    <b-tab title="Attestations">
                        <attestations-list :volunteer="volunteer"></attestations-list>
                    </b-tab>
                    <b-tab title="Trainings">
                        <trainings-list :volunteer="volunteer"></trainings-list>
                    </b-tab>
                    <b-tab title="Documents">
                        <documents-card :volunteer="volunteer"></documents-card>
                    </b-tab>
                    <b-tab title="Priorities" v-if="volunteer.isComprehensive()">
                        <priorities-list :volunteer="volunteer"></priorities-list>
                    </b-tab>
                    <b-tab title="Survey Data">
                        <survey-responses :volunteer="volunteer"></survey-responses>
                    </b-tab>
                    <b-tab title="Notes" v-if="user.hasPermission('create notes')">
                        <notes-list notable-type="App\User" :notable-id="volunteer.id"></notes-list>
                    </b-tab>
                </b-tabs>
            </div>
        </div>
        <b-modal title="Update Status" hide-footer v-model="showStatusForm">
            <status-form
                :volunteer="volunteer"
                @updatevolunteer="reloadVolunteer"
                @no-change="showStatusForm = false"
            ></status-form>
        </b-modal>
        <comprehensive-convert-confirmation
            :volunteer="volunteer"
            v-model="showVolunteerTypeForm"
            @saved="handleVolunteerConversion"
        />
        <b-modal
            v-model="showImpersonatingProgress"
            hide-footer
            hide-header
            no-close-on-backdrop
            no-close-on-escape
            hide-header-close
        >
            <h3 class="text-center">Impersonating {{ volunteer.name }}.</h3>
            <p class="text-center">
                The page will reload in a moment...
            </p>
        </b-modal>

    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import findVolunteer from '../../resources/volunteers/find_volunteer'
    import impersonateUser from '../../resources/users/impersonate_user'

    import volunteerSummary from './partials/tabs/VolunteerSummary.vue'
    import SurveyResponses from './partials/tabs/SurveyResponses.vue'

    import AttestationsList from './partials/AttestationsList.vue';
    import volunteerStatusAlert from './partials/VolunteerStatusAlert.vue'
    import ApplicationData from './partials/ApplicationData.vue'
    import PrioritiesList from './partials/PrioritiesList.vue'
    import StatusForm from './partials/StatusForm.vue'
    import Volunteer from '../../entities/volunteer'
    import DocumentsCard from './partials/DocumentsCard.vue'
    import TrainingsList from './partials/TrainingsList.vue'
    import DemographicInfo from './partials/DemographicInfo.vue'
    import ComprehensiveConvertConfirmation from './ComprehensiveConvertConfirmation.vue'

    export default {
        props: {
            id: {
                type: Number,
                required: true
            },
            initialVolunteer: {
                type: Object,
                default() {
                    return {};
                }
            }
        },
        components: {
            volunteerSummary,
            volunteerStatusAlert,
            ApplicationData,
            PrioritiesList,
            StatusForm,
            DocumentsCard,
            AttestationsList,
            SurveyResponses,
            TrainingsList,
            DemographicInfo,
            ComprehensiveConvertConfirmation
        },
        data() {
            return {
                testing: true,
                loading: false,
                volunteer: new Volunteer(this.initialVolunteer),
                application: {},
                showStatusForm: false,
                showVolunteerTypeForm: false,
                newStatus: null,
                showImpersonatingProgress: false
            }
        },
        computed: {
            isComprehensive: function () {
                return this.volunteer.volunteer_type_id == 2;
            },
            ...mapGetters({user: 'getUser'})
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
            handleVolunteerConversion() {
                this.findVolunteer()
                this.showVolunteerTypeForm = false
            },
            handleUpdate(updatedVolunteer) {
                if (updatedVolunteer) {
                    this.volunteer = updatedVolunteer
                    return;
                }
                this.findVolunteer()
            },
            impersonateVolunteer()
            {
                this.showImpersonatingProgress = true;
                impersonateUser(this.volunteer.id);
            }
        },
        created() {
            if (this.initialVolunteer == {}) {
                this.findVolunteer()
            }
        }

}
</script>
