

<template>
    <attestation-form title="Volunteer Variant Curator Attestation" :signable="allYes">
        <p>
            <strong>
                Please review the statements below and check "Yes" or "No", then sign your name to signify that you have reviewed these training materials.
            </strong>
        </p>
        
        <question-block>
            <div slot="question-text">
                I have read the current ACMG/AMP sequence variant interpretation guidelines (Richard et al., 2015). *
            </div>
            <radio-group
                slot="answer-block"
                name="readGuidelines"
                v-model="readGuidelines"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
            ></radio-group>
        </question-block>

        <question-block>
            <div slot="question-text">
                I have read the ClinGen General Sequence Variant Curation Process SOP. *
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
                I have reviewed the SVI recommendations for the ACMG/AMP criteria. *
            </div>
            <radio-group
                slot="answer-block"
                name="reviewedSviRecommendations"
                v-model="reviewedSviRecommendations"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
            ></radio-group>
        </question-block>

        <question-block>
            <div slot="question-text">
                I have watched the training video on literature searching for variant curation. *
            </div>
            <radio-group
                slot="answer-block"
                name="watchedLiteratureTraining"
                v-model="watchedLiteratureTraining"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
            ></radio-group>
        </question-block>

        <question-block>
            <div slot="question-text">
                I have watched the training video on use of the ClinGen Allele Registry. *
            </div>
            <radio-group
                slot="answer-block"
                name="watchedAlleleVideo"
                v-model="watchedAlleleVideo"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
            ></radio-group>
        </question-block>

        <question-block>
            <div slot="question-text">
               I have attended a live ClinGen Variant Curation Webex training session. *
            </div>
            <radio-group
                slot="answer-block"
                name="attendedTraining"
                v-model="attendedTraining"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
            ></radio-group>
        </question-block>

        <div slot="signature-text">
            I, {{attestation.user.name}}, attest that as of {{today}} I have completed all the elements of ClinGen Variant Curation Training. *Please sign your name below (e.g., John Doe). *
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
                readGuidelines: null,
                readSOP: null,
                reviewedSviRecommendations: null,
                watchedLiteratureTraining: null,
                watchedAlleleVideo: null,
                attendedTraining: null,
                today: moment().format('YYYY-MM-DD')
            }
        },
        computed: {
            allYes: function () {
                return this.readGuidelines === 1
                        && this.readSOP === 1
                        && this.watchedLiteratureTraining === 1
                        && this.reviewedSviRecommendations === 1
                        && this.watchedAlleleVideo === 1
                        && this.attendedTraining === 1;
            }
        }
    
}
</script>