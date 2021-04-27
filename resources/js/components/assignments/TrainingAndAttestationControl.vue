<template>
    <div>
        <div v-if="userAptitude.trained_at == null">
            <div class="form-inline">
                <div v-if="$store.state.user.notVolunteer()">
                    <validation-error :errors="errors.trained_at"></validation-error>
                    <div v-show="setTrainingDate" class="form-inline">
                        <label>Date completed:</label>
                        &nbsp;
                        <date-input v-model="newTrainingCompletedDate" class="form-control form-control-sm"></date-input>
                        
                        <button class="d-block btn btn-sm btn-primary" @click="markTrainingCompleted(userAptitude)">Save</button>
                    </div>
                    <button class="btn btn-sm btn-primary" v-show="!setTrainingDate" @click="setTrainingDate = true">Mark {{userAptitude.aptitude.name}} Training complete</button>
                </div>
            </div>
        </div>
        <div v-else>
            <div v-if="!userAptitude.attestation.signed_at">
                <a 
                    :href="`/attestations/${userAptitude.attestation.id}/edit`" 
                    class="btn btn-sm btn-primary"
                >Sign attestation</a>
            </div>
        </div>
    </div>
</template>
<script>
import markTrainingComplete from '../../resources/trainings/mark_training_complete'

export default {
    props: {
        userAptitude: {
            required: true
        },
    },
    data() {
        return {
            newTrainingCompletedDate: null,
            setTrainingDate: false,
            errors: {}
        }
    },
    methods: {
        markTrainingCompleted(aptitude) {
            markTrainingComplete(aptitude.id, this.newTrainingCompletedDate)
                .then(() => this.$emit('trainingcompleted', aptitude))
                .catch(error => {
                    if (error.response && error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }
                });
        },

        emitTrainingCompleted(training) {
            this.$emit('trainingcompleted', {
                id: training.id,
                trained_at: this.newTrainingCompletedDate
            })
        }
    }
}
</script>