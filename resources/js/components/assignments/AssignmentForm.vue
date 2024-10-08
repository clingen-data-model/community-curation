

<template>
    <div>
        <h4>
            Assign Activity and Curation Group
            <span v-if="showVolunteer"> - {{volunteer.name}}</span>
        </h4>
        <hr>
        <div>
            <h5>Volunteer Priorities</h5>
            <priorities-list
                :volunteer="volunteer"
                :disable-set-new="true"
                :disable-view-complete="true"
                @saved="$emit('saved')"
            />
        </div>
        <div class="mt-4">
            <h5>
                <!-- <b-icon-arrow-repeat class="float-right"></b-icon-arrow-repeat> -->
                Assignments
            </h5>

            <div class="position-relative">
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
                                <div>
                                    <delete-button class="float-right" @click="confirmUnassign(assignment)"></delete-button>
                                    <strong>{{assignment.assignable.name}}</strong>
                                    <status-badge :assignment="assignment"
                                        @assignmentsupdated="$emit('assignmentsupdated')"
                                    ></status-badge>
                                </div>
                                <secondary-aptitude-control
                                    :assignment="assignment"
                                    @trainingcompleted="$emit('saved')"
                                    @assignAptitude="createAptitudeTraining($event, assignment)"
                                    class="mt-1"
                                ></secondary-aptitude-control>
                            </td>
                            <td colspan="2">
                                <div v-if="assignment.user_aptitudes.pending().primary().length > 0">
                                    <training-and-attestation-control
                                        :userAptitude="assignment.user_aptitudes.pending().primary().get(0)"
                                        @trainingcompleted="$emit('saved')"
                                    ></training-and-attestation-control>
                                </div>

                                <div v-else>
                                    <curation-group-cell
                                        v-if="assignment.assignable.curation_activity_type_id == 1"
                                        :assignment="assignment"
                                        :curation-groups="getCurationGroupsForCurationActivity(assignment.assignable.id)"
                                        :volunteer="volunteer"
                                        @save="saveNewAssignment('App\\CurationGroup', $event)"
                                        @assignmentsupdated="$emit('assignmentsupdated')"
                                        @unassign="removeAssignment($event)"
                                    ></curation-group-cell>

                                    <gene-group-selector
                                        v-if="assignment.assignable.curation_activity_type_id == 2"
                                        :assignment="assignment"
                                        :volunteer="volunteer"
                                        @save="saveNewAssignment('App\\Gene', $event)"
                                        @assignmentsupdated="$emit('assignmentsupdated')"
                                        @unassign="removeAssignment($event)"
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
    </div>
</template>

<script>
    import {mapMutations} from 'vuex'
    import createAssignment from '../../resources/assignments/create_assignment'
    import createTraining from '../../resources/trainings/create_training'
    import getAllCurationGroups from '../../resources/curation_groups/get_all_curation_groups'
    import getAllAptitudes from '../../resources/aptitudes/get_all_aptitudes'
    import deleteAssignment from '../../resources/assignments/delete_assignment'

    import CurationGroupCell from './CurationGroupCell'
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
            },
            updating: {
                required: false,
                default: false
            },
            showVolunteer: {
                required: false,
                default: false,
                type: Boolean
            }
        },
        emits: [
            'saved',
            'assignmentsupdated',
        ],
        components: {
            CurationGroupCell,
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
                        curationGroup: 'test EP'
                    },
                    {
                        activity: 'Dosage',
                        curationGroup: 'test EP'
                    }
                ],
                newCurationActivity: null,
                newCurationGroupId: null,
                curationActivities: [],
                curationGroups: [],
                aptitudes: [],
                configs: window.configs,
            }
        },
        computed: {
            unassignedCurationGroups: function () {
                const assignedPanels = Object.values(this.volunteer.assignments.map(ac => ac.sub_assignments)).flat().map(subAss => subAss.assignable);
                return this.curationGroups.filter(ep => assignedPanels.map(ep => ep.id).indexOf(ep.id) == -1)
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
            ...mapMutations('messages', ['addInfo']),
            fetchCurationGroups: async function()
            {
                this.curationGroups = await getAllCurationGroups();
            },
            fetchAptitudes: async function ()
            {
                this.aptitudes = await getAllAptitudes();
            },
            getCurationGroupsForCurationActivity(curationActivityId) {
                return this.unassignedCurationGroups
                        .filter(panel => {
                            return panel.curation_activity_id == curationActivityId;
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
            createAptitudeTraining(aptitude, assignment) {
                let data = {
                    aptitude_id: aptitude.id,
                    user_id: this.volunteer.id,
                    assignment_id: assignment.id,
                };

                createTraining(data)
                    .then(response => this.$emit('saved'));
            },
            confirmUnassign(assignment) {
                if (confirm('You are about the unassign '+this.volunteer.name+' from '+assignment.assignable.name+" and all related panels/genes.\n\nThis cannot be undone.")) {
                    this.removeAssignment(assignment);
                }
            },
            removeAssignment(assignment)
            {
                deleteAssignment(assignment)
                    .then(response => {
                        this.addInfo(this.volunteer.name+' has been unassigned from '+assignment.assignable.name);
                        this.$emit('saved')
                    })
                    .catch(error => {
                        console.error(error);
                        if (error.response) {
                            alert(error.response.statusMessage)
                        }
                    })
            }
        },
        mounted() {
            this.fetchCurationGroups();
            this.fetchAptitudes();
        }

}
</script>
