<style></style>

<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <assignment-card 
                    :volunteer="volunteer"
                    v-on:updatevolunteer="$emit('updatevolunteer')"
                ></assignment-card>
                <!-- <div class="card p-3 mt-4">
                    <div class="card-hearder">
                        <h4>Aptitudes &amp; trainings</h4>
                    </div>
                </div> -->
            </div>

            <div class="col-md-6">
                <div class="card p-3 mb-3">
                    <h4>
                        <span>Contact Information</span>
                        <button class="btn btn-sm btn-default border float-right" @click="modalEdit = !modalEdit">Edit</button>
                    </h4>
                    
                    <dl class="row">
                        <!-- <dt class="col-sm-4">Volunteer Status:</dt>
                        <dd class="col-sm-8">
                            {{volunteer.volunteer_status.name || 'loading...'}}
                            &nbsp;
                            <button 
                                class="btn btn-sm btn-default border" 
                                @click="$emit('updatestatus')"
                                v-if="!$store.state.user.isVolunteer()"
                            >update</button>
                        </dd> -->
                        <dt class="col-sm-3">Address:</dt>
                        <dd class="col-sm-9">
                            <span v-if="volunteer.street1">{{ volunteer.street1 }}, </span>
                            <span v-if="volunteer.street2">{{ volunteer.street2 }}, </span>
                            <span v-if="volunteer.city">{{ volunteer.city }}, </span>
                            <span v-if="volunteer.state">{{ volunteer.state }}, </span>
                            <span v-if="volunteer.country_id">{{ volunteer.country }}</span>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <b-modal v-model="modalEdit">Hello From Modal!</b-modal>
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
        data() {
            return {
                modalEdit: false,
            }
        },
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