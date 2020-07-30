<template>
    <attestation-form title="Actionability Volunteer Attestation" :signable="allYes">
        <p><strong>Please review the statements below and check "Yes" or "No". Then sign your name to signify that you have reviewed these training materials.</strong></p>

        <question-block>
            <div slot="question-text">
                I have read through the slides entitled "An Introduction to the ClinGen Actionability Working Group" and understand how clinical actionability is described and assessed by ClinGen. *
            </div>
            <radio-group
                slot="answer-block"
                name="read_introduction"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
                v-model="readIntroduction"
            ></radio-group>
        </question-block>

        <question-block>
            <div slot="question-text">
                I have reviewed the "Early Rule-Out Instructions" slides and understand the process for gene-disease topic research and documentation. *
            </div>
            <radio-group
                slot="answer-block"
                name="read_rule_out"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
                v-model="readRuleOut"
            ></radio-group>
        </question-block>

        <question-block>
            <div slot="question-text">
                I have completed the four example topics and completed the Early Rule-Out online form for each one.
            </div>
            <radio-group
                slot="answer-block"
                name="completed_examples"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
                v-model="completedExamples"
            ></radio-group>
        </question-block>

        <question-block>
            <div slot="question-text">
                I have attended a webinar training session to review my four completed practice topics and ask any questions that came up. 
            </div>
            <radio-group
                slot="answer-block"
                name="attended_webinar"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
                v-model="attendedWebinar"
            ></radio-group>
        </question-block>
        
        
        <div slot="signature-text">I, {{attestation.user.name}}, attest that as of {{signedAt}} I have completed all the elements of the ClinGen Actionability Rule-Out Training.</div>
    </attestation-form>
</template>

<script>
    import moment from 'moment';

    export default {
        props: {
            attestation: {
                required: true,
                type: Object
            }
        },
        data() {
            return {
                readIntroduction: null,
                readRuleOut: null,
                completedExamples: null,
                attendedWebinar: null,
                signature: null,
                signedAt: moment().format('YYYY-MM-DD')
            }
        },
        computed: {
            allYes: function () {
                return this.readIntroduction === 1
                        && this.readRuleOut === 1
                        && this.completedExamples === 1 
                        && this.attendedWebinar === 1;
            }
        },
    
}
</script>