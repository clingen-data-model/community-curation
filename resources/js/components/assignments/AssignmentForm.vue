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
            
            <table class="table table-sm table-bordered mb-1">
                <thead>
                    <tr>
                        <th style="width:30%">Curation Activities:</th>
                        <th>
                            <span v-if="volunteer.isComprehensive()">Panels / </span>Genes:
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="assignment in volunteer.assignments" :key="assignment.id">
                        <td
                            :class="{'text-strike text-muted': (assignment.assignment_status_id == $store.state.configs.project.assignmentStatuses.retired)}"
                        >
                            {{assignment.assignable.name}}
                            <status-badge :assignment="assignment"
                                @assignmentsupdated="$emit('assignmentsupdated')"
                            ></status-badge>
                            <secondary-aptitude-control 
                                :assignment="assignment"
                                @assignAptitude="createAptitudeTraining($event, assignment)"
                                @trainingcompleted="markTrainingCompleted"
                            ></secondary-aptitude-control>
                        </td>
                        <td>
                            <div v-if="assignment.user_aptitudes.pending().primary().length() > 0">
                                <training-and-attestation-control
                                    :userAptitude="assignment.user_aptitudes.pending().primary().get(0)"
                                    @trainingcompleted="markTrainingCompleted"
                                ></training-and-attestation-control>
                            </div>

                            <div v-else>
                                <expert-panel-cell 
                                    v-if="assignment.assignable.curation_activity_type_id == 1"
                                    :assignment="assignment" 
                                    :expert-panels="getExpertPanelsForCurationActivity(assignment.assignable.id)"
                                    :volunteer="volunteer"
                                    @save="saveNewAssignment('App\\ExpertPanel', $event)"
                                    @assignmentsupdated="$emit('assignmentsupdated')"
                                ></expert-panel-cell>

                                <gene-group-selector 
                                    v-if="assignment.assignable.curation_activity_type_id == 2"
                                    :assignment="assignment"
                                    :volunteer="volunteer"
                                    @save="saveNewAssignment('App\\Gene', $event)"
                                    @assignmentsupdated="$emit('assignmentsupdated')"
                                ></gene-group-selector>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="d-flex justify-content-between">
                <assign-activity-button :volunteer="volunteer"
                    @activity_selected="saveNewAssignment('App\\CurationActivity', $event)"
                    v-if="volunteer.isComprehensive()"
                ></assign-activity-button>
                <assign-baseline-button :volunteer="volunteer"
                    @assigned="saveNewAssignment('App\\CurationActivity', $event)"
                ></assign-baseline-button>
            </div>
        </div>
    </div>
</template>

<script>
    import createAssignment from '../../resources/assignments/create_assignment'
    import markTrainingComplete from '../../resources/trainings/mark_training_complete'
    import createTraining from '../../resources/trainings/create_training'
    import getAllExpertPanels from '../../resources/expert_panels/get_all_expert_panels'
    import getAllAptitudes from '../../resources/aptitudes/get_all_aptitudes'
    
    import ExpertPanelCell from './ExpertPanelCell'
    import GeneGroupSelector from './GeneGroupSelector'
    import PrioritiesList from '../volunteers/partials/PrioritiesList'
    import TrainingAndAttestationControl from './TrainingAndAttestationControl'
    import StatusBadge from './StatusBadge'
    import AssignActivityButton from './AssignActivityButton'
    import AssignBaselineButton from './AssignBaselineButton'
    import SecondaryAptitudeControl from '../aptitudes/SecondaryAptitudeControl'

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
            StatusBadge,
            AssignActivityButton,
            AssignBaselineButton,
            SecondaryAptitudeControl
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
                newCurationActivity: null,
                newExpertPanelId: null,
                curationActivities: [],
                expertPanels: [],
                aptitudes: [],
                configs: window.configs
            }
        },
        computed: {
            unassignedExpertPanels: function () {
                const assignedPanels = Object.values(this.volunteer.assignments.map(ac => ac.sub_assignments)).flat().map(subAss => subAss.assignable);
                return this.expertPanels.filter(ep => assignedPanels.map(ep => ep.id).indexOf(ep.id) == -1)  
            },
            geneticEvidenceAptitude: function () {
                return this.aptitudes.find(item => item.name === 'Baseline, Genetic Evidence');
            },
            baselineAssignment: function () {
                const blAssignment = this.volunteer.assignments.find(item => item.assignable.name == 'Baseline');
                return blAssignment.curationActivity;
            }
        },
        methods: {
            fetchExpertPanels: async function()
            {
                this.expertPanels = await getAllExpertPanels();
            },
            fetchAptitudes: async function ()
            {
                this.aptitudes = await getAllAptitudes();
            },
            getExpertPanelsForCurationActivity(curationActivityId) {
                return this.unassignedExpertPanels
                        .filter(panel => {
                            return panel.curation_activity_id == curationActivityId
                                && panel.accepting_volunteers == 1
                        })
            },
            saveNewAssignment(assignableType, assignable) {
                createAssignment({
                    assignable_type: assignableType,
                    assignable_id: assignable.id,
                    user_id: this.volunteer.id
                })
                .then(response => {
                    this.$emit('saved');
                })
            },
            markTrainingCompleted({id, trained_at}) {
                markTrainingComplete(id, trained_at)
                    .then(() => this.$emit('saved'));                
            },
            createAptitudeTraining(aptitude, assignment) {
                let data = {
                    aptitude_id: aptitude.id,
                    user_id: this.volunteer.id,
                    assignment_id: assignment.id,
                };

                createTraining(data)
                    .then(response => this.$emit('saved'));
            }
        },
        mounted() {
            this.fetchExpertPanels();
            this.fetchAptitudes();
        }
    
}
</script>