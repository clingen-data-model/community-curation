<template>
    <attestation-form title="Baseline Annotation Attestation" :signable="allYes">
        <question-block>
            <template slot="question-text">
                I have watched the Baseline Annotation tutorial and understand the process of web assisted annotation? 
            </template>
            <radio-group  slot="answer-block"
                name="watched_anotation_tutorial"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
                v-model="watched_anotation_tutorial"
            ></radio-group>
        </question-block>

        <question-block>
            <template slot="question-text">
                I have read the Hypothes.is Annotation Overview?
            </template>
            <radio-group  slot="answer-block"
                name="read_hypothesis_overview"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
                v-model="read_hypothesis_overview"
            ></radio-group>
        </question-block>

        <question-block>
            <template slot="question-text">
                I have created a hypothes.is user account?
            </template>
            <radio-group  slot="answer-block"
                name="created_hypothesis_account"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
                v-model="created_hypothesis_account"
            ></radio-group>
        </question-block>

        <question-block>
            <template slot="question-text">
                I have attended/reviewed the live web conference training session for Baseline Annotation?
            </template>
            <radio-group  slot="answer-block"
                name="attended_web_training"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
                v-model="attended_web_training"
            ></radio-group>
        </question-block>
        <div slot="signature-text">I, {{attestation.user.name}}, attest that as of {{signedAt}} I have completed all the elements of the ClinGen Baseline Annotation Training.</div>
    </attestation-form>
</template>
<script>
import moment from 'moment';

export default {
    props: {
        attestation: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            watched_anotation_tutorial: null,
            read_hypothesis_overview: null,
            created_hypothesis_account: null,
            attended_web_training: null,
            signature: null,
            signedAt: moment().format('YYYY-MM-DD')
        }
    },
    computed: {
        allYes: function () {
            return this.watched_anotation_tutorial === 1
                    && this.read_hypothesis_overview === 1
                    && this.created_hypothesis_account === 1 
                    && this.attended_web_training === 1;
        }
    },
}
</script>