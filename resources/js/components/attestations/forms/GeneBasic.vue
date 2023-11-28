

<template>
    <attestation-form title="Volunteer Gene Curator Attestation" :signable="canSubmit">
        <p>
            <strong>
                Please review the statements below and check "Yes" or "No". Then sign your name to signify that you have reviewed these training materials.
            </strong>
        </p>

        <attestation-question
            v-for="(question, idx) in questions" :key="idx"
            :question="question.question"
            value="question.value"
            :name="question.name"
            :required="question.required"
            @input="$set(question, 'value', $event);"
        >
        </attestation-question>

        <div slot="signature-text">
            I, {{attestation.user.name}}, attest that as of {{today}} I have completed all the elements of the Gene Disease Validity Training. *
        </div>
    </attestation-form>
</template>

<script>
    import moment from 'moment'
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
                        question: "I have reviewed the Gene Curation Standard Operating Procedures and understand this curation process. *",
                        name: "watched_sop_video",
                        value: null,
                        required: true
                    },
                    {
                        question: "I have reviewed the Lumping and Splitting Curation Guidelines and understand this curation process. *",
                        name: "reviewed_lumping_and_splitting_guidelines",
                        value: null,
                        required: true
                    },
                    {
                        question: "I have reviewed the Standardized Evidence Summary Text guidelines and understand this process. *",
                        name: "read_standardized_evidence_summary",
                        value: null,
                        required: true
                    },
                    {
                        question: "I have watched the Gene curation classifications video and understand the ClinGen Gene-Disease Validity classification system. *",
                        name: "watched_gene_curation_classificaitons",
                        value: null,
                        required: true
                    },
                    {
                        question: "I have watched the gene curation scoring overview video and understand the basics of this framework. *",
                        name: "watched_gne_curation_scoring_overview",
                        value: null,
                        required: true
                    },
                    {
                        question: "I have watched the lumping and splitting tutorial and understand the process of defining the most appropriate disease entity. *",
                        name: "watch_lumping_and_splitting_tutorial",
                        value: null,
                        required: true
                    },
                    {
                        question: "I have reviewed the interactive example powerpoints and understand the process that goes into curating different inheritance patterns. *",
                        name: "reviewed_example_powerpoints",
                        value: null,
                        required: true
                    },
                    {
                        question: "I have attended the live virtual training session for the Gene Curation Interface (GCI). *",
                        name: "attended_live_virtual_training",
                        value: null,
                        required: true
                    }
                ],
                canSubmit: false,
                today: moment().format('YYYY-MM-DD')
            }
        },
    watch: {
        questions: {
            deep: true,
            handler: function() {
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
        }
    }
}
</script>