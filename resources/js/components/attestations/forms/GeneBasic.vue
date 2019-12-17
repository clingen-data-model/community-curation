<style></style>

<template>
    <attestation-form title="Volunteer Gene Curator Attestation" :signable="allYes">
        <p>
            <strong>
                Please review the statements below and check "Yes" or "No". Then sign your name to signify that you have reviewed these training materials.
            </strong>
        </p>
        
        <question-block>
            <div slot="question-text">
                I have watched the Gene curation classifications video and understand the ClinGen Gene-Disease Validity classification system. *
            </div>
            <radio-group
                slot="answer-block"
                name="watchedClassificationVideo"
                v-model="watchedClassificationVideo"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
            ></radio-group>
        </question-block>

        <question-block>
            <div slot="question-text">
                I have watched the gene curation scoring overview video and understand the basics of this framework. *
            </div>
            <radio-group
                slot="answer-block"
                name="watchedScoring"
                v-model="watchedScoring"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
            ></radio-group>
        </question-block>

        <question-block>
            <div slot="question-text">
                I have watched the lumping and splitting tutorial and understand the process of defining the most appropriate disease entity. *
            </div>
            <radio-group
                slot="answer-block"
                name="watchedLumpingAndSplitting"
                v-model="watchedLumpingAndSplitting"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
            ></radio-group>
        </question-block>

        <question-block>
            <div slot="question-text">
                I have reviewed the interactive example powerpoints and understand the process that goes into curating different inheritance patterns. *
            </div>
            <radio-group
                slot="answer-block"
                name="reviewedPowerpoints"
                v-model="reviewedPowerpoints"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
            ></radio-group>
        </question-block>

        <question-block>
            <div slot="question-text">
                I have reviewed the Gene Curation Standard Operating Procedures and understand this curation process. *
            </div>
            <radio-group
                slot="answer-block"
                name="reviewedSOP"
                v-model="reviewedSOP"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
            ></radio-group>
        </question-block>

        <question-block>
            <div slot="question-text">
                I have attended the live Webex session training for the Gene Curation Interface (GCI). *
            </div>
            <radio-group
                slot="answer-block"
                name="attendedTraining"
                v-model="attendedTraining"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
            ></radio-group>
        </question-block>

        <div slot="signature-text">
            I, {{attestation.user.name}}, attest that as of {{today}} I have completed all the elements of the Gene Disease Validity Training. *
        </div>
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
                watchedClassificationVideo: null,
                watchedScoring: null,
                watchedLumpingAndSplitting: null,
                reviewedPowerpoints: null,
                reviewedSOP: null,
                attendedTraining: null,
                today: moment().format('YYYY-MM-DD')
            }
        },
        computed: {
            allYes: function () {
                return this.watchedClassificationVideo === 1
                        && this.watchedScoring === 1
                        && this.reviewedPowerpoints === 1
                        && this.watchedLumpingAndSplitting === 1
                        && this.reviewedSOP === 1
                        && this.attendedTraining === 1;
            }
        }
    
}
</script>