<template>
    <ul class="list-unstyled">
        <li v-for="training in assignment.trainings" :key="training.id">
            <div v-if="training.completed_at == null">
                <div class="form-inline">
                    <div v-if="$store.state.user.notVolunteer()">
                        <div v-show="setTrainingDate" class="form-inline">
                            <label>Date completed:</label>
                            &nbsp;
                            <date-field v-model="newTrainingCompletedDate" class="form-control form-control-sm"></date-field>
                            <button class="btn btn-sm btn-primary" @click="emitTrainingCompleted(training)">Save</button>
                        </div>
                        <button class="btn btn-sm btn-primary" v-show="!setTrainingDate" @click="setTrainingDate = true">Mark {{training.aptitude.name}} Training complete</button>
                    </div>
                </div>
            </div>
            <div v-else>
                <div v-if="assignment.attestations[0].signed_at == null">
                    <a 
                        :href="`/attestations/${assignment.attestation.id}/edit`" 
                        class="btn btn-sm btn-primary"
                    >Sign attestation</a>
                </div>
                <!-- awaiting attestation -->
            </div>
        </li>
    </ul>
</template>
<script>
export default {
    props: {
        assignment: {
            required: true
        },
    },
    data() {
        return {
            newTrainingCompletedDate: null,
            setTrainingDate: false            
        }
    },
    computed: {

    },
    methods: {
        emitTrainingCompleted(training) {
            this.$emit('trainingcompleted', {
                id: training.id,
                completed_at: this.newTrainingCompletedDate
            })
        }
    }
}
</script>