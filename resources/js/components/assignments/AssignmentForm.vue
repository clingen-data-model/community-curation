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
                        <th>
                            <span v-if="volunteer.isComprehensive()">Panels / </span>Genes:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="assignment in activityCurationAssignments" :key="assignment.curationActivity.id">
                        <td
                            :class="{'text-strike text-muted': (assignment.curationActivity.assignment_status_id == $store.state.configs.project.assignmentStatuses.retired)}"
                        >
                            {{assignment.curationActivity.assignable.name}}
                            <status-badge :assignment="assignment.curationActivity"
                                @assignmentsupdated="$emit('assignmentsupdated')"
                            ></status-badge>
                        </td>
                        <td>
                            <training-and-attestation-control 
                                v-if="assignment.needsAptitude"
                                :assignment="assignment"
                                v-on:trainingcompleted="markTrainingCompleted"
                            ></training-and-attestation-control>

                            <div v-else>
                                <expert-panel-cell 
                                    v-if="assignment.curationActivity.assignable.curation_activity_type_id == 1"
                                    :assignment="assignment" 
                                    :expert-panels="getExpertPanelsForCurationActivity(assignment.curationActivity.assignable.id)"
                                    :volunteer="volunteer"
                                    @save="saveNewAssignment('App\\ExpertPanel', $event)"
                                    @assignmentsupdated="$emit('assignmentsupdated')"
                                ></expert-panel-cell>

                                <gene-group-selector 
                                    v-if="assignment.curationActivity.assignable.curation_activity_type_id == 2"
                                    :assignment="assignment"
                                    :volunteer="volunteer"
                                    @save="saveNewAssignment('App\\Gene', $event)"
                                    @assignmentsupdated="$emit('assignmentsupdated')"
                                ></gene-group-selector>
                            </div>
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
                                :disabled="volunteer.volunteer_status_id == $store.state.configs.volunteers.statuses.retired"
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
    import GeneGroupSelector from './GeneGroupSelector'
    import PrioritiesList from '../volunteers/partials/PrioritiesList'
    import TrainingAndAttestationControl from './TrainingAndAttestationControl'
    import StatusBadge from './StatusBadge'

    export default {
        props: {
            volunteer: {
                required: true
            }
        },
        components: {
            ExpertPanelCell,
            PrioritiesList,
            TrainingAndAttestationControl,
            GeneGroupSelector,
            StatusBadge
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
                const assignedPanels = Object.values(this.activityCurationAssignments.map(ac => ac.subAssignments)).flat().map(subAss => subAss.assignable);
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
            saveNewAssignment(assignableType, assignable) {
                console.log(assignable);
                createAssignment({
                    assignable_type: assignableType,
                    assignable_id: assignable.id,
                    user_id: this.volunteer.id
                })
                .then(response => {
                    this.cancelAddingActivity();
                    this.$emit('saved');
                })
            },
            saveNewCurationActivity() {
                this.saveNewAssignment('App\\CurationActivity', this.newCurationActivity);
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