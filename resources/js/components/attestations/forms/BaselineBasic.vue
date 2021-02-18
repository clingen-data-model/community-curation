<template>
    <attestation-form title="Baseline Annotation Attestation" :signable="canSubmit">
        <p>Please review the statements below and check "Yes" or "No". Then sign your name to signify that you have reviewed these training materials.</p>

        <attestation-question
            v-for="(question, idx) in questions" :key="idx"
            :question="question.question"
            value="question.value"
            :name="question.name"
            :required="question.required"
            @input="$set(question, 'value', $event);"
        >
        </attestation-question>


        <question-block v-show="showHypothesisId" class="form-inline ml-4">
            <template slot="question-text">
                My hypothes.is username:&nbsp;
            </template>
            <input type="text" v-model="hypothesis_id" slot="answer-block" class="form-control" name="hypothesis_id" @input="checkCanSubmit()">
        </question-block>


        <div slot="signature-text">I, {{attestation.user.name}}, attest that as of {{signedAt}} I have completed all the elements of the Baseline Basic evidence Training.</div>
    </attestation-form>
</template>
<script>
import moment from 'moment';
import AttestationQuestion from './AttestationQuestion'

export default {
    components: {
        AttestationQuestion
    },
    props: {
        attestation: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            questions: [
                {
                    name: "reviewed_annotation_overview",
                    question: "I have reviewed the Baseline Annotation Overview and understand this curation process. *",
                    value: null,
                    required: true,
                },
                {
                    name: "reviewed_protocol_review",
                    question: "I have reviewed the Baseline Annotation Protocol Review and understand this resource. *",value: null,
                    required: "true",
                },
                {            
                    name: "watched_variants_and_nomenclature",
                    question: "I have watched the “Introduction to Variants and Nomenclature” video and understand thi *s process.",
                    value: null,
                    required: "true"
                },
                {
                    name: "watched_into_to_genome_build_and_transcripts",
                    question: "I have watched the “Introduction to genome builds and transcripts” video and understan *d this process." ,
                    value: null,
                    required: "true"
                },
                {
                    name: 'watched_allele_registry_overview',
                    question: "I have watched the “Basic Overview of using the ClinGen Allele Registry” video an *d understand this resource.",
                    value: null,
                    required: true
                },
                {
                    name: 'watched_utilizing_clinvar',
                    question: 'I have watched the “Utilizing ClinVar for Allele Identifiers” video and understand thi *s resource.',
                    value: null,
                    required: true
                },
                {
                    name: 'attended_live_training',
                    question: 'I have attended the live, interactive virtual training session for Baseline Annotation. *',
                    value: null,
                    required: true
                },
                {
                    name: 'registered_with_hypothesis',
                    question: 'I have registered for a hypothes.is user account. *',
                    value: null,
                    required: true,
                }
            ],
            hypothesis_id: this.attestation.user.hypothesis_id,
            showHypothesisId: this.attestation.user.hypothesis_id ? true : false,
            signedAt: moment().format('YYYY-MM-DD'),
            canSubmit: false
        }
    },
    watch: {
        questions: {
            deep: true,
            handler: function() {
                this.toggleHypothesisIdQuestion();
                this.checkCanSubmit();
            }
        }
    },
    methods: {
        checkCanSubmit() {
                for (let idx in this.questions.filter(q => q.required)) {
                    console.info('question:', this.questions[idx])
                    if (this.questions[idx].value != 1) {
                        this.canSubmit = false;
                        return;
                    }
                }
                if (this.hypothesis_id === null || this.hypothesis_id == '') {
                    this.canSubmit = false;
                    return;
                }

                this.canSubmit = true;                
        },
        toggleHypothesisIdQuestion() {
            if (this.questions.find(i => i.name == 'registered_with_hypothesis').value == 1) {
                this.showHypothesisId = true;
                return;
            }
            this.showHypothesisId = false;
            this.hypothesis_id = this.attestation.user.hypothesis_id;

        }
    }
}
</script>