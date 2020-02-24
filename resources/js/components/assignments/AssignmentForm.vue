<style></style>

<template>
    <div>
        <h3>Assign Activity and Expert Panel</h3>
        <hr>
        <div>
            <h5>Volunteer Priorities</h5>
            <priorities-list :volunteer="volunteer" :disable-set-new="true" :disable-view-complete="true"></priorities-list>
        </div>
        <div class="mt-4">
            <h5>Assignments</h5>
            <button 
                @click="addingCurationActivity = true"
                class="btn btn-default form-control btn-primary" 
                v-if="activityCurationAssignments.length == 0 && !addingCurationActivity"
            >
                Assign Volunteer to a Curation Activity
            </button>
            <table class="table table-sm table-bordered" v-if="activityCurationAssignments.length > 0 || addingCurationActivity">
                <thead>
                    <tr>
                        <th style="width:30%">Curation Activities:</th>
                        <th>Expert Panels:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="assignment in activityCurationAssignments" :key="assignment.curationActivity.id">
                        <td
                            :class="{'text-strike text-muted': (assignment.curationActivity.assignment_status_id == $store.state.configs.project.assignmentsStatuses.retired)}"
                        >
                            {{assignment.curationActivity.assignable.name}}
                        </td>
                        <td>
                            <expert-panel-cell 
                                :assignment="assignment" 
                                :expert-panels="getExpertPanelsForCurationActivity(assignment.curationActivity.assignable.id)"
                                :volunteer="volunteer"
                                v-on:save="saveNewExpertPanel"
                                v-on:trainingcompleted="markTrainingCompleted"
                            ></expert-panel-cell>
                        </td>
                    </tr>
                    <tr v-if="addingCurationActivity">
                        <td>
                            <select v-model="newCurationActivity" class="form-control form-control-sm">
                                <option :value="null">Select&hellip;</option>
                                <option v-for="(activity, idx) in unassignedCurationActivities" :key="idx" :value="activity">
                                    {{activity.name}}
                                </option>
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary" @click="saveNewCurationActivity">Save</button>
                            <button class="btn btn-sm btn-default" @click="cancelAddingActivity">Cancel</button>
                        </td>
                    </tr>
                    <tr v-if="!addingCurationActivity" class="border-top pt-2">
                        <td colspan="2">
                            <button 
                                class="btn btn-default border btn-sm" 
                                @click="addingCurationActivity = true"
                                :disabled="volunteer.volunteer_status_id == $store.state.configs.volunteers.retired"
                            >
                                Add Curation Activity
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import createAssignment from '../../resources/assignments/create_assignment'
    import getAllCurationActivities from '../../resources/curation_activities/get_all_curation_activities'
    import markTrainingComplete from '../../resources/trainings/mark_training_complete'
    import getAllExpertPanels from '../../resources/expert_panels/get_all_expert_panels'
    
    import ExpertPanelCell from './ExpertPanelCell'
    import PrioritiesList from '../volunteers/partials/PrioritiesList'

    export default {
        props: {
            volunteer: {
                required: true
            }
        },
        components: {
            ExpertPanelCell,
            PrioritiesList
        },
        data() {
            return {
                priorities: [
                    {
                        activity: 'Gene',
                        expertPanel: 'test EP'
                    },
                    {
                        activity: 'Dosage',
                        expertPanel: 'test EP'
                    }
                ],
                addingCurationActivity: false,
                newCurationActivity: null,
                newExpertPanelId: null,
                curationActivities: [],
                expertPanels: [],
                configs: window.configs
            }
        },
        computed: {
            activityCurationAssignments: function () {
                // return [];
                return this.volunteer.assignments
            },
            unassignedCurationActivities: function () {
                const actAssIds = this.activityCurationAssignments.map(assignment => assignment.curationActivity.assignable_id);
                return this.curationActivities
                    .filter((activity) => {
                        return actAssIds.indexOf(activity.id) == -1
                    })
            },
            unassignedExpertPanels: function () {
                const assignedPanels = Object.values(this.activityCurationAssignments.map(ac => ac.expertPanels)).flat().map(epAss => epAss.assignable);
                return this.expertPanels.filter(ep => assignedPanels.map(ep => ep.id).indexOf(ep.id) == -1)  
            }
        },
        methods: {
            fetchCurationActivities: async function()
            {
                this.curationActivities = await getAllCurationActivities();
            },
            fetchExpertPanels: async function()
            {
                this.expertPanels = await getAllExpertPanels();
            },
            cancelAddingActivity() {
                this.newCurationActivity = null;
                this.addingCurationActivity = false;
            },
            getExpertPanelsForCurationActivity(curationActivityId) {
                return this.unassignedExpertPanels
                        .filter(panel => {
                            return panel.curation_activity_id == curationActivityId
                                && panel.accepting_volunteers == 1
                        })
            },
            saveNewCurationActivity() {
                createAssignment({
                    assignable_type: 'App\\CurationActivity',
                    assignable_id: this.newCurationActivity.id,
                    user_id: this.volunteer.id
                })
                .then(response => {
                    this.cancelAddingActivity();
                    this.$emit('saved');
                })
            },
            saveNewExpertPanel(expertPanel) {
                createAssignment({
                    assignable_type: 'App\\ExpertPanel',
                    assignable_id: expertPanel.id,
                    user_id: this.volunteer.id
                })
                .then(response => {
                    this.cancelAddingActivity();
                    this.$emit('saved');
                })
            },
            markTrainingCompleted({id, completed_at}) {
                markTrainingComplete(id, completed_at)
                    .then(() => this.$emit('saved'));                
            },
        },
        mounted() {
            this.fetchCurationActivities();
            this.fetchExpertPanels();
        }
    
}
</script>