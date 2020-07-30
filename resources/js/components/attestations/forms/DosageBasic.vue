

<template>
    <attestation-form :title="'Dosage Volunteer Attestation'" :signable="allYes">
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
                name="read_dosage_eval_process"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
                v-model="readDosageEvalProcess"
            ></radio-group>
        </question-block>

        <div slot="signature-text">
            I, {{attestation.user.name}}, attest that as of {{signedAt}}, I have completed all the elements of the Dosage Sensitivity Curation training
        </div>
    </attestation-form>
</template>

<script>
    import moment from 'moment';

    export default {
        props: {
            attestation: Object
        },
        data() {
            return {
                attendedTraining: null,
                readDosageEvalProcess: null,
                signature: null,
                signedAt: moment().format('YYYY-MM-DD'),
            }
        },
        computed: {
            allYes: function () {
                return this.attendedTraining === 1
                        && this.readDosageEvalProcess === 1;
            },
        }
    
}
</script>