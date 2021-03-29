

<template>
    <attestation-form title="Somatic Cancer Volunteer Curator Attestation" :signable="allYes">
        <p>
            <strong>
                Please review the statements below and check "Yes" or "No". Then sign your name to signify that you have reviewed these training materials.
            </strong>
        </p>
        
        <question-block>
            <div slot="question-text">
                 I have attended the live ClinGen Somatic – CIViC training Session.
            </div>
            <radio-group
                slot="answer-block"
                name="attendedCIVicTraining"
                v-model="attendedCIVicTraining"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
            ></radio-group>
        </question-block>

        <question-block>
            <div slot="question-text">
                   I have created a CIViC account
            </div>
            <radio-group
                slot="answer-block"
                name="createdCIVicAccount"
                v-model="createdCIVicAccount"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
            ></radio-group>
        </question-block>

        <question-block class="mb-4">
            <div slot="question-text">
                I have chosen a curation activity. (Please list your choice below)
            </div>
            <div slot="answer-block" class="form-inline">
                <input type="text" name="chosenCurationActivity" v-model="chosenCurationActivity" class="form-control">
            </div>
        </question-block>

        <question-block>
            <div slot="question-text">
                I have signed up for a practice curation assignment in CIViC: 
                <a href="https://docs.google.com/spreadsheets/d/1vBDR3xVaKgkOSW_7VTO8Mxe2wo1WCJV1mbT3nO1lCM4/edit#gid=0"  target="practice-session">
                    https://docs.google.com/spreadsheets/d/1vBDR3xVaKgkOSW_7VTO8Mxe2wo1WCJV1mbT3nO1lCM4/edit#gid=0
                </a>
            </div>
            <radio-group
                slot="answer-block"
                name="signedUpForPractice"
                v-model="signedUpForPractice"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
            ></radio-group>
        </question-block>

        <br>
        
        <div slot="signature-text">I, {{attestation.user.name}}, attest that as of {{signedAt}} I have completed all the elements of the Somatic Cancer Training.</div>
    </attestation-form>
</template>

<script>
    import moment from 'moment'

    export default {
        props: {
            attestation: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                attendedCIVicTraining: null,
                createdCIVicAccount: null,
                chosenCurationActivity: null,
                signedUpForPractice: null,
                signedAt: moment().format('YYYY-MM-DD') 
            }
        },
        computed: {
            allYes: function () {
                return this.attendedCIVicTraining === 1
                        && this.createdCIVicAccount === 1
                        && this.chosenCurationActivity != '' && this.chosenCurationActivity !== null
                        && this.signedUpForPractice === 1
            }
        }
    
}
</script>