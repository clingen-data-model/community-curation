<style></style>

<template>
        <div class="card p-3">
            <h4>{{(volunteer.volunteer_type.name || 'loading...') | ucfirst }} Volunteer</h4>
            <div v-if="!hasAssignments">
                <div class="text-muted" v-if="$store.state.user.isVolunteer()">
                    A ClinGen staff member will contact you shortly about your curation activity assignment.
                </div>
                <div v-else>
                    <button 
                        class="btn btn-lg btn-primary"
                        @click="showAssignmentForm = true"
                    >
                        Assign Curation Activity
                    </button>
                </div>
            </div>
            <div v-else>
                <table class="table table-sm" v-if="hasAssignments">
                    <thead>
                        <tr>
                            <th style="width: 30%">Curation Activity</th>
                            <th>Expert Panel</th>
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
                                    Needs Aptitude
                                </div>
                                <div v-else>
                                    <span v-for="(ep, idx) in assignment.expertPanels" :key="idx"
                                        :class="{'text-strike text-muted': assignmentIsRetired(ep)}"
                                    >
                                        {{ep.assignable.name}}
                                    </span>
                                </div>
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
            <b-modal v-model="showAssignmentForm" hide-header hide-footer>
                <assignment-form :volunteer="volunteer" @saved="$emit('updatevolunteer')"></assignment-form>
            </b-modal>
        </div>
</template>

<script>
    export default {
        props: {
            volunteer: {
                reqired: true,
                type: Object
            }
        },
        data() {
            return {
                showAssignmentForm: false,
            }
        },
        computed: {
            hasAssignments: function (){
                return this.volunteer.assignments && this.volunteer.assignments.length > 0
            },
        },
        methods: {
            assignmentIsRetired(assignment) {
                return assignment.assignment_status_id == 2;
            }
        } 
    }
</script>