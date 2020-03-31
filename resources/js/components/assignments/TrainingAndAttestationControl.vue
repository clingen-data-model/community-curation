<template>
    <div>
        <div v-if="userAptitude.trained_at == null">
            <div class="form-inline">
                <div v-if="$store.state.user.notVolunteer()">
                    <div v-show="setTrainingDate" class="form-inline">
                        <label>Date completed:</label>
                        &nbsp;
                        <date-field v-model="newTrainingCompletedDate" class="form-control form-control-sm"></date-field>
                        <button class="btn btn-sm btn-primary" @click="emitTrainingCompleted(userAptitude)">Save</button>
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
export default {
    props: {
        userAptitude: {
            required: true
        },
    },
    data() {
        return {
            newTrainingCompletedDate: null,
            setTrainingDate: false            
        }
    },
    methods: {
        emitTrainingCompleted(training) {
            this.$emit('trainingcompleted', {
                id: training.id,
                trained_at: this.newTrainingCompletedDate
            })
        }
    }
}
</script>