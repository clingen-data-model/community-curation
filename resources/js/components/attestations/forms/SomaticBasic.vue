<template>
    <attestation-form title="Somatic Cancer Volunteer Curator Attestation" :signable="allYes">
        <p>
            <strong>
                Please review the statements below and check "Yes" or "No". Then sign your name to signify that you have reviewed these training materials.
            </strong>
        </p>
        
        <question-block>
            <div slot="question-text" class="mb-1">
                 I have attended the live ClinGen Somatic – CIViC training Session.
            </div>
            <radio-group
                slot="answer-block"
                name="attendedCIVicTraining"
                v-model="attendedCIVicTraining"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
            ></radio-group>

        </question-block>

        <div v-show="attendedCIVicTraining === 0" class="pl-4">
            <question-block>
                <div slot="question-text">
                        I have watched the "CIViC - Getting Started" video.
                </div>
                <radio-group 
                    slot="answer-block" 
                    name="watchedCIVicGettingStarted"
                    v-model="watchedCIVicGettingStarted"
                    :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
                    ></radio-group>
            </question-block>
            
            <question-block>
                <div slot="question-text">
                        I have watched the "CIViC - Adding Evidence" video.
                </div>
                <radio-group 
                    slot="answer-block" 
                    name="watchedCIVicAddingEvidence"
                    v-model="watchedCIVicAddingEvidence"
                    :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
                    ></radio-group>
            </question-block>

            <question-block>
                <div slot="question-text">
                        I have watched the "CIViC - Editing Entities" video.
                </div>
                <radio-group 
                    slot="answer-block" 
                    name="watchedCIVicEditingEntities"
                    v-model="watchedCIVicEditingEntities"
                    :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
                    ></radio-group>
            </question-block>

            <question-block>
                <div slot="question-text">
                        I have watched the "CIViC - Adding Source Suggestions" video.
                </div>
                <radio-group 
                    slot="answer-block" 
                    name="watchedCIVicAddingSourceSuggestions"
                    v-model="watchedCIVicAddingSourceSuggestions"
                    :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
                    ></radio-group>
            </question-block>

            <question-block>
                <div slot="question-text">
                        I have read the “Somatic cancer variant curation and harmonization through consensus minimum variant level data” pape.
                </div>
                <radio-group 
                    slot="answer-block" 
                    name="readCurationAndHarmonization"
                    v-model="readCurationAndHarmonization"
                    :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
                    ></radio-group>
            </question-block>
        </div>

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

        <question-block v-if="createdCIVicAccount == 1" class="pl-4 mb-4" style="display:flex">
            <div slot="question-text">
                Please provide your CIViC username:
            </div>
            <div slot="answer-block" class="form-inline ml-2">
                <input class="form-control form-control-sm" type="text" name="CIViCUsername" v-model="CIViCUsername">
            </div>
        </question-block>

        <question-block class="mb-4">
            <div slot="question-text">
                I have chosen a curation activity. (Please list your choice below)
            </div>
            <div slot="answer-block" class="form-inline">
                <input type="text" name="chosenCurationActivity" v-model="chosenCurationActivity" class="form-control form-control-sm">
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
                watchedCIVicGettingStarted: null,
                watchedCIVicAddingEvidence: null,
                watchedCIVicEditingEntities: null,
                watchedCIVicAddingSourceSuggestions: null,
                readCurationAndHarmonization: null,
                createdCIVicAccount: null,
                CIViCUsername: null,
                chosenCurationActivity: null,
                signedUpForPractice: null,

                signedAt: moment().format('YYYY-MM-DD') 
            }
        },
        computed: {
            civicQuestionsCompleted() {
                if (this.attendedCIVicTraining === 0) {
                    return this.watchedCIVicGettingStarted == 1
                        && this.watchedCIVicAddingEvidence == 1
                        && this.watchedCIVicEditingEntities == 1
                        && this.watchedCIVicAddingSourceSuggestions == 1
                        && this.readCurationAndHarmonization == 1;
                } else {
                    return true;
                }
            },
            allYes: function () {
                return this.civicQuestionsCompleted
                        && this.createdCIVicAccount === 1
                        && this.CIViCUsername != '' && this.CIViCUsername !== null
                        && this.chosenCurationActivity != '' && this.chosenCurationActivity !== null
                        && this.signedUpForPractice === 1;
            }
        }
    
}
</script>