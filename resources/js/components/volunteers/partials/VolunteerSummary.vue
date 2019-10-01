<style></style>

<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <assignment-card :volunteer="volunteer"></assignment-card>
                <!-- <div class="card p-3 mt-4">
                    <div class="card-hearder">
                        <h4>Aptitudes &amp; trainings</h4>
                    </div>
                </div> -->
            </div>

            <div class="col-md-6">
                <div class="card p-3 mb-3">
                    <h4>Basic Information</h4>
                    <dl class="row">
                        <dt class="col-sm-4">Volunteer Syatus:</dt>
                        <dd class="col-sm-8">
                            {{volunteer.volunteer_status.name || 'loading...'}}
                            &nbsp;
                            <button 
                                class="btn btn-sm btn-default border" 
                                @click="$emit('updatestatus')"
                                v-if="!$store.state.user.isVolunteer()"
                            >update</button>
                        </dd>
                        <dt class="col-sm-4">Email:</dt>
                        <dd class="col-sm-8">
                            <a :href="'mailto:'+volunteer.email">{{volunteer.email || 'loading...'}}</a>
                        </dd>

                        <dt class="col-sm-4" v-if="volunteer.phone">Phone:</dt>
                        <dd class="col-sm-8" v-if="volunteer.phone">{{volunteer.phone || '&nbsp;'}}</dd>

                        <dt class="col-sm-4" v-if="volunteer.address">Address:</dt>
                        <dd class="col-sm-8" v-if="volunteer.address">{{volunteer.address || '&nbsp;'}}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AssignmentCard from './AssignmentCard'

    export default {
        components: {
            AssignmentCard
        },
        props: {
            volunteer: {
                reqired: true,
                type: Object
            }
        },
        // data() {
        //     return {
        //         showAssignmentForm: false,
        //     }
        // },
        // computed: {
        //     hasAssignments: function (){
        //         return this.volunteer.assignments && this.volunteer.assignments.length > 0
        //     },
        // },
        // methods: {
        //     assignmentIsRetired(assignment) {
        //         return assignment.assignment_status_id == 2;
        //     }
        // } 
    }
</script>