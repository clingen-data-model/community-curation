<template>
        <div class="card p-3">
            <h4>
                {{(volunteer.volunteer_type.name || 'loading...') | ucfirst }} Volunteer
                    - <b-badge>{{volunteer.volunteer_status.name}}</b-badge>
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
                <table class="table table-striped tabel-sm">
                    <thead>
                        <tr>
                            <th style="width: 40%">Curation Activity</th>
                            <th colspan="2">
                                <span v-if="volunteer.isComprehensive()">Panel / </span>
                                Genes
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="asn in volunteer.assignments" :key="asn.id">
                            <td>
                                <strong>{{asn.assignable.name}}</strong>
                                <b-badge>{{asn.status.name}}</b-badge>
                                <div v-if="asn.user_aptitudes.granted().secondary().length > 0">
                                    <small>
                                        <ul class="list-unstyled ml-2 mt-0">
                                            <li v-for="trn in asn.user_aptitudes.granted().secondary()" :key="trn.id">
                                                {{trn.aptitude.name}}
                                            </li>
                                        </ul>
                                    </small>
                                </div>
                            </td>
                            <td>
                                <assignment-summary-column :assignment="asn" @manageAssignments="showAssignmentForm = true"></assignment-summary-column>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button
                    class="btn btn btn-default border btn-xs"
                    @click="showAssignmentForm = true"
                    :disabled="volunteer.volunteer_status_id == $store.state.configs.volunteers.statuses.retired"
                    v-if="$store.state.user.isAdminOrProgrammer()"
                >
                    Manage Assignments
                </button>
            </div>
            <b-modal v-model="showAssignmentForm" hide-header hide-footer v-if="$store.state.user.isAdminOrProgrammer()" size="lg">
                <assignment-form
                    :volunteer="volunteer"
                    @saved="$emit('updatevolunteer')"
                    @assignmentsupdated="$emit('updatevolunteer')"
                ></assignment-form>
            </b-modal>
        </div>
</template>

<script>
    import AssignmentStatusForm from './AssignmentStatusForm';
    import UserAptitudeCollection from '../../../collections/user_aptitude_collection'
    import AssignmentSummaryColumn from '../assignments/AssignmentSummaryColumn'

    export default {
        components: {
            AssignmentStatusForm,
            AssignmentSummaryColumn
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
                if (this.currentAssignment && this.currentAssignment !== {}) {
                    const as = this.volunteer.assignments.find((ass) => {
                        return ass.curation_activity_id == this.currentAssignment.curation_activity_id
                    });
                    this.syncCurrentAssignment(as)
                }
            }
        },
        methods: {
            assignmentIsRetired(assignment) {
                return assignment.assignment_status_id == this.$store.state.configs.project.assignmentStatuses.retired;
            },
            syncCurrentAssignment(assignment) {
                this.currentAssignment = assignment
            },
            signAttestation(id) {
                axios.put('/api/dev/sign-attestation/'+id)
                    .then(() => this.$emit('updatevolunteer'));
            }
        }
    }
</script>
