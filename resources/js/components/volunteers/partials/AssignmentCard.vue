<style></style>

<template>
        <div class="card p-3">
            <h4>
                {{(volunteer.volunteer_type.name || 'loading...') | ucfirst }} Volunteer
                    - {{volunteer.volunteer_status.name}}
            </h4>
            <div v-if="!hasAssignments">
                <only-volunteer class="text-muted">
                    A ClinGen staff member will contact you shortly about your curation activity assignment.
                </only-volunteer>
                <non-volunteer>
                    <button 
                        class="btn btn-lg btn-primary"
                        @click="showAssignmentForm = true"
                    >
                        Assign Curation Activity
                    </button>
                </non-volunteer>
            </div>
            <div v-else>
                <table class="table table-sm" v-if="hasAssignments">
                    <thead>
                        <tr>
                            <th style="width: 30%">Curation Activity</th>
                            <th colspan="2">Expert Panel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(assignment, idx) in volunteer.assignments" :key="idx">
                            <td
                                :class="{'text-strike text-muted': assignmentIsRetired(assignment.curationActivity)}"
                            >
                                {{assignment.curationActivity.assignable.name}}
                            </td>
                            <td>
                                <div v-if="assignment.needsAptitude" class="text-muted">
                                    <div v-if="assignment.training.completed_at === null">
                                        <only-volunteer>
                                            <a :href="assignment.curationActivity.assignable.aptitudes[0].training_materials_url" 
                                                class="btn btn-sm btn-primary"
                                                target="training"
                                            >
                                                Start training
                                            </a>
                                        </only-volunteer>
                                        <non-volunteer>
                                            Needs training
                                        </non-volunteer>
                                    </div>
                                    <div v-else>
                                        <only-volunteer>
                                            <a href="#" 
                                                class="btn btn-sm btn-primary"
                                                target="attestation"
                                                :href="`/attestations/${assignment.attestation.id}/edit`"
                                                v-if="!assignment.attestation.signed_at"
                                            >
                                                Sign Attestation
                                            </a>
                                        </only-volunteer>
                                        <non-volunteer>
                                            awaiting attestation
                                        </non-volunteer>
                                    </div>
                                </div>
                                <div v-else>
                                    <span v-for="(ep, idx) in assignment.expertPanels" :key="idx"
                                        :class="{'text-strike text-muted': assignmentIsRetired(ep)}"
                                    >
                                        {{ep.assignable.name}}<span v-if="idx < assignment.expertPanels.length-1 ">,</span>
                                    </span>
                                </div>
                            </td>
                            <td class="text-right">
                                <non-volunteer>
                                    <button 
                                        class="btn btn-default btn-xs border"
                                        @click="editActivityAssignment(assignment)"
                                    >
                                        Edit Status
                                    </button>
                                </non-volunteer>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button 
                    class="btn btn btn-default border btn-sm"
                    @click="showAssignmentForm = true"
                    :disabled="volunteer.volunteer_status_id == 3"
                    v-if="!$store.state.user.isVolunteer()"
                >
                    Edit
                </button>
            </div>
            <b-modal v-model="showAssignmentForm" hide-header hide-footer v-if="!$store.state.user.isVolunteer()" size="lg">
                <assignment-form :volunteer="volunteer" @saved="$emit('updatevolunteer')"></assignment-form>
            </b-modal>
            <b-modal v-model="showAssignmentStatusForm" 
                hide-header 
                hide-footer
                 v-if="!$store.state.user.isVolunteer()"
            >
                <assignment-status-form
                    :assignment="currentAssignment"
                    @assignmentsupdated="$emit('updatevolunteer')"
                >
                </assignment-status-form>
            </b-modal>
        </div>
</template>

<script>
    import AssignmentStatusForm from './AssignmentStatusForm';

    export default {
        components: {
            AssignmentStatusForm
        },
        props: {
            volunteer: {
                reqired: true,
                type: Object
            }
        },
        data() {
            return {
                showAssignmentForm: false,
                showAssignmentStatusForm: false,
                currentAssignment: {},
            }
        },
        computed: {
            hasAssignments: function (){
                return this.volunteer.assignments && this.volunteer.assignments.length > 0
            },
        },
        watch: {
            volunteer: function (to, from) {
                console.log(this.currentAssignment);
                if (this.currentAssignment !== {}) {
                    const as = this.volunteer.assignments.find((ass) => {
                        return ass.curation_activity_id == this.currentAssignment.curation_activity_id
                    });
                    this.syncCurrentAssignment(as)
                }
            }
        },
        methods: {
            assignmentIsRetired(assignment) {
                return assignment.assignment_status_id == 4;
            },
            editActivityAssignment(assignment) {
                this.syncCurrentAssignment(assignment);
                this.showAssignmentStatusForm = true
            },
            syncCurrentAssignment(assignment) {
                this.currentAssignment = assignment
            },
            // TODO: to be removed when attestations are build
            signAttestation(id) {
                axios.put('/api/dev/sign-attestation/'+id)
                    .then(() => this.$emit('updatevolunteer'));
            }
        } 
    }
</script>