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
                                <span v-if="volunteer.isComprehensive()">Panel / </span>Gene
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="asn in volunteer.assignments" :key="asn.id">
                            <td>
                                {{asn.assignable.name}} <b-badge>{{asn.status.name}}</b-badge>
                            </td>
                            <td>
                                <ul v-if="asn.sub_assignments.length > 0"
                                    class="list-unstyled"
                                >
                                    <li v-for="subAsn in asn.sub_assignments" :key="subAsn.id">
                                        <small>{{subAsn.assignable.name}}</small>
                                        <a 
                                            v-if="subAsn.assignable.protocol_path"
                                            :href="`/genes/${subAsn.assignable.symbol}/protocol`"
                                            class="float-right"
                                        >
                                            Protocol
                                        </a>
                                    </li>
                                </ul>
                                <div v-if="asn.user_aptitudes.filter(trn => trn.trained_at == null).length > 0">
                                    <small>
                                        <strong>Pending Training:</strong>
                                        <ul class="list-unstyled ml-2 mt-0">
                                            <li v-for="trn in asn.user_aptitudes.untrained()" :key="trn.id">
                                                {{trn.aptitude.name}}
                                            </li>
                                        </ul>
                                    </small>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button 
                    class="btn btn btn-default border btn-sm"
                    @click="showAssignmentForm = true"
                    :disabled="volunteer.volunteer_status_id == $store.state.configs.volunteers.statuses.retired"
                    v-if="!$store.state.user.isVolunteer()"
                >
                    Manage Assignments
                </button>
            </div>
            <b-modal v-model="showAssignmentForm" hide-header hide-footer v-if="!$store.state.user.isVolunteer()" size="lg">
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