<template>
    <div>
        <div v-if="loading" class="text-center py-5">
            <b-spinner label="Loading survey..."></b-spinner>
            <p class="mt-2">Loading survey...</p>
        </div>

        <div v-else-if="error" class="alert alert-danger">
            {{ error }}
        </div>

        <div v-else-if="completed" class="alert alert-success text-center py-4">
            <h4>Thank you!</h4>
            <p>Your survey response has been submitted successfully.</p>
            <a :href="redirectUrl" class="btn btn-primary mt-2">Continue</a>
        </div>

        <div v-else>
            <div v-if="finalized" class="alert alert-warning">
                This survey has already been submitted and cannot be modified.
            </div>
            <div id="survey-container"></div>
        </div>
    </div>
</template>

<script>
import { Model } from 'survey-core';
import { Survey } from 'survey-vue';

export default {
    components: {
        Survey,
    },
    props: {
        surveySlug: {
            type: String,
            required: true,
        },
        redirectUrl: {
            type: String,
            default: '/',
        },
    },
    data() {
        return {
            loading: true,
            error: null,
            completed: false,
            finalized: false,
            surveyModel: null,
            responseId: null,
        };
    },
    async mounted() {
        try {
            const [definitionRes, responseRes] = await Promise.all([
                window.axios.get(`/api/surveys/${this.surveySlug}/definition`),
                window.axios.get(`/api/surveys/${this.surveySlug}/response`),
            ]);

            const definition = definitionRes.data;
            const existingResponse = responseRes.data;

            if (existingResponse && existingResponse.finalized_at) {
                this.finalized = true;
            }

            this.surveyModel = new Model(definition);

            if (existingResponse && existingResponse.response_data) {
                this.responseId = existingResponse.id;
                this.surveyModel.data = existingResponse.response_data;
                if (existingResponse.last_page) {
                    this.surveyModel.currentPageNo = this.surveyModel.getPageIndexByName(existingResponse.last_page);
                }
            }

            if (this.finalized) {
                this.surveyModel.mode = 'display';
            }

            this.surveyModel.onComplete.add(this.onSurveyComplete);
            this.surveyModel.onCurrentPageChanged.add(this.onPageChanged);

            this.loading = false;

            this.$nextTick(() => {
                if (this.$el.querySelector('#survey-container')) {
                    new Survey({
                        propsData: { model: this.surveyModel },
                    }).$mount('#survey-container');
                }
            });
        } catch (err) {
            console.error('Failed to load survey:', err);
            this.error = 'Failed to load the survey. Please try again later.';
            this.loading = false;
        }
    },
    methods: {
        async onSurveyComplete(sender) {
            try {
                await window.axios.post(`/api/surveys/${this.surveySlug}/response`, {
                    response_data: sender.data,
                    last_page: sender.currentPage ? sender.currentPage.name : null,
                    finalize: true,
                });
                this.completed = true;
            } catch (err) {
                console.error('Failed to submit survey:', err);
                this.error = 'Failed to submit your response. Please try again.';
            }
        },
        async onPageChanged(sender) {
            try {
                await window.axios.post(`/api/surveys/${this.surveySlug}/response`, {
                    response_data: sender.data,
                    last_page: sender.currentPage ? sender.currentPage.name : null,
                    finalize: false,
                });
            } catch (err) {
                console.error('Failed to save progress:', err);
            }
        },
    },
};
</script>
