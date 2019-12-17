<style></style>

<template>
    <attestation-form title="Somatic Cancer Volunteer Curator Attestation" :signable="allYes">
        <p>
            <strong>
                Please review the statements below and check "Yes" or "No". Then sign your name to signify that you have reviewed these training materials.
            </strong>
        </p>
        <question-block>
            <div slot="question-text">
                I have reviewed the CIViC Knowledge Model and Standard Operating Procedures for Curation and Clinical Interpretation of Variants in Cancer: 
                <a href="https://www.ncbi.nlm.nih.gov/pubmed/27814769" target="civic-sop">https://www.ncbi.nlm.nih.gov/pubmed/27814769</a>
            </div>
            <radio-group
                slot="answer-block"
                name="readSOP"
                v-model="readSOP"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
            ></radio-group>
        </question-block>

        <question-block>
            <div slot="question-text">
                I have watched the "CIViC - Getting Started" video.
            </div>
            <radio-group
                slot="answer-block"
                name="watchedGettingStarted"
                v-model="watchedGettingStarted"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
            ></radio-group>
        </question-block>

        <question-block>
            <div slot="question-text">
                I have watched the "CIViC - Adding Evidence" video. 
            </div>
            <radio-group
                slot="answer-block"
                name="watchedAddingEvidence"
                v-model="watchedAddingEvidence"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
            ></radio-group>
        </question-block>

        <question-block>
            <div slot="question-text">
                I have watched the "CIViC - Editing Entities" video.
            </div>
            <radio-group
                slot="answer-block"
                name="watchedEditingEntities"
                v-model="watchedEditingEntities"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
            ></radio-group>
        </question-block>

        <question-block>
            <div slot="question-text">
                I have created an account in CIViC
            </div>
            <radio-group
                slot="answer-block"
                name="createdCIVicAccount"
                v-model="createdCIVicAccount"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
            ></radio-group>
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

        <question-block>
            <div slot="question-text">
                I have chosen a curation activity/disease task force. (Please list your choice below)
            </div>
            <div slot="answer-block" class="form-inline">
                <input type="text" name="chosenTaskForce" v-model="chosenTaskForce" class="form-control">
            </div>
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
                readSOP: null,
                watchedGettingStarted: null,
                watchedAddingEvidence: null,
                watchedEditingEntities: null,
                createdCIVicAccount: null,
                signedUpForPractice: null,
                chosenTaskForce: null,
                signedAt: moment().format('YYYY-MM-DD') 
            }
        },
        computed: {
            allYes: function () {
                return this.readSOP === 1
                        && this.watchedGettingStarted === 1
                        && this.watchedAddingEvidence === 1
                        && this.watchedEditingEntities === 1
                        && this.createdCIVicAccount === 1
                        && this.signedUpForPractice === 1
                        && this.chosenTaskForce !== null
            }
        }
    
}
</script>