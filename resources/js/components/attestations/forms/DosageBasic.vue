<style></style>

<template>
    <div class="w-75 m-auto">
    <b-card>
        <h2 slot="header">
            ClinGen Dosage Volunteers
        </h2>

        <form @submit.prevent="submitAttestation" class="sirs-survey">
            <question-block>
                <template slot="question-text">
                    I have attended the live Zoom session training for Dosage Sensitivity Curation. 
                </template>
                <radio-group  slot="answer-block"
                    name="attended_zoom_training"
                    :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
                    v-model="attendedTraining"
                ></radio-group>
            </question-block>

            <question-block>
                <template slot="question-text">
                    I have read the original publication outlining the dosage evaluation process (Riggs et al., 2012).
                </template>
                <radio-group  slot="answer-block"
                    name="readDosageEvalProcess"
                    :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
                    v-model="readDosageEvalProcess"
                ></radio-group>
            </question-block>

            <div class="d-flex" style="font-size: 1rem" v-show="allYes">
                <div>
                    <input 
                        type="checkbox" 
                        v-model="signiture" 
                        :value="1" 
                        id="sig-checkbox" 
                        class="mr-3 pt-1" 
                        style="transform: scale(1.5);"
                        :disabled="!allYes"
                    >
                </div>
                <label for="sig-checkbox">
                    I, {{this.attestation.user.name}}, attest that as of {{date}}, I have completed all the elements of the Dosage Sensitivity Curation training
                </label>
            </div>

        </form>

        <div slot="footer">
            <button class="btn btn-default" @click="cancelFormSubmission">Cancel</button>
            <button class="btn btn-primary" :disabled="!allAnswered" @click="submitAttestation">Complete Attestation</button>
        </div>        
    </b-card>
    </div>
</template>

<script>
    import moment from 'moment';
    import submitAttestation from '../../../resources/attestations/submitAttestation'

    export default {
        props: {
            attestation: Object
        },
        data() {
            return {
                attendedTraining: null,
                readDosageEvalProcess: null,
                signiture: null,
                date: null,
                submitting: false
            }
        },
        computed: {
            allYes: function () {
                return this.attendedTraining && this.readDosageEvalProcess;
            },
            allAnswered: function () {
                return this.allYes && this.signiture && this.date
            }
        },
        methods: {
            cancelFormSubmission() {
                window.history.back();
            },
            submitAttestation() {
                this.submitting = true;
                const data = {
                    attendedTraining: this.attendedTraining,
                    readDosageEvalProcess: this.readDosageEvalProcess,
                    signiture: this.signiture,
                    date: this.date                        
                }
                submitAttestation(this.attestation.id, data)
                    .then(response => {
                        console.log('signed attestation');
                        this.submitting = false;
                        window.history.back();
                    });
            }
        },
        mounted() {
            this.date = moment().format('YYYY-MM-DD')
        }
    
}
</script>