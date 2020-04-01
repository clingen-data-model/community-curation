<template>
    <attestation-form title="Baseline Annotation Attestation" :signable="allYes">
        <question-block>
            <template slot="question-text">
                Genetic evidence attestation question 1.
            </template>
            <radio-group  slot="answer-block"
                name="q1"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
                v-model="question1"
            ></radio-group>
        </question-block>

        <question-block>
            <template slot="question-text">
                Genetic evidence attestation question 2.
            </template>
            <radio-group  slot="answer-block"
                name="q2"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
                v-model="question_2"
            ></radio-group>
        </question-block>

        <question-block>
            <template slot="question-text">
                Genetic evidence attestation question 3.
            </template>
            <radio-group  slot="answer-block"
                name="q3"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
                v-model="question_3"
            ></radio-group>
        </question-block>

        <question-block>
            <template slot="question-text">
                Genetic evidence attestation question 4.
            </template>
            <radio-group  slot="answer-block"
                name="q4"
                :options="[{label: 'Yes', value: 1}, {label: 'No', value: 0}]"
                v-model="question_4"
            ></radio-group>
        </question-block>
        <div slot="signature-text">I, {{attestation.user.name}}, attest that as of {{signedAt}} I have completed all the elements of the ClinGen Baseline Genetic Evidence Training.</div>
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
            question1: null,
            question_2: null,
            question_3: null,
            question_4: null,
            signature: null,
            signedAt: moment().format('YYYY-MM-DD')
        }
    },
    computed: {
        allYes: function () {
            return this.question1 === 1
                    && this.question_2 === 1
                    && this.question_3 === 1 
                    && this.question_4 === 1;
        }
    },
}
</script>