<template>
    <div>
        <div v-if="!volunteer.hasDemographicInfo() && !responded">
            <div class="alert alert-info">
                <h5>We have some demographic questions.</h5>
                <p>
                    Please share a little about yourself to help us understand who volunteers with C3. 
                    These questions are optional. If you'd prefer not to share, just hit the "I'd rather not" button below.
                </p>
                
                <button class="btn btn-sm btn-light border" @click="declineDemographics">I'd rather not</button>
                <button class="btn btn-sm btn-primary" @click="showForm">Sure, I'll share</button>
            </div>
        </div>
        <b-modal
            hide-footer
            v-model="formVisible"
            size="lg"
            @hidden="cancelForm"
            title="Demographic Questions"
        >
            <question-block v-if="showSelfDesc">
                <div slot="question-text">
                    <strong>How would you best describe yourself?</strong>
                </div>
                <div slot="answer-block">
                    <b-form-radio-group stacked class="pl-2">
                        <b-form-radio v-for="option in selfDescriptions" v-model="selfDesc" :value="option.id" :key="option.id">
                            {{option.name}}
                        </b-form-radio>
                        <div class="form-inline">
                            <b-form-radio v-model="selfDesc" value="100">
                                Other
                            </b-form-radio>
                            &nbsp;
                            <b-form-input v-if="selfDesc == 100" v-model="selfDescOther" placeholder="details"></b-form-input>
                        </div>
                    </b-form-radio-group>                    
                </div>
            </question-block>

            <question-block v-if="showEducation">
                <div slot="question-text">
                    <strong>What is your highest level of education?</strong>
                </div>
                <div slot="answer-block">
                    <b-form-radio-group stacked class="pl-2">
                        <b-form-radio v-for="(label, id) in educationLevels" v-model="education" :value="id" :key="id">
                            {{label}}
                        </b-form-radio>
                        <div class="form-inline">
                            <b-form-radio v-model="education" value="100">
                                Other
                            </b-form-radio>
                            &nbsp;
                            <b-form-input v-if="education == 100" v-model="educationOther" placeholder="details"></b-form-input>
                        </div>
                    </b-form-radio-group>
                </div>
            </question-block>

            <question-block v-if="showRace">
                <div slot="question-text">
                    <strong>What is your race/ethnicity?</strong>
                    <small v-b-toggle.race-ethnicity-info>More info about this question</small>
                    <b-collapse id="race-ethnicity-info">
                        <div class="alert alert-info">
                            <h5>Why are we asking this question?</h5>
                            <p>
                                ClinGen strives to be a diverse and inclusive organization. 
                                We're asking you about your race and ethnicity so that we can evaluate our efforts to include people with various racial and ethnic identities.
                            </p>

                            <h5>About the options</h5>
                            <p>
                                ClinGen is a National Institutes of Health (NIH)-funded organization. The options below come from the NIH 
                                <a target="nih" href="https://grants.nih.gov/grants/guide/notice-files/not-od-15-089.html">Racial and Ethnic Categories and Definitions for NIH Diversity Programs and for Other Reporting Purposes</a>.
                                We understand that these options do not capture the diversity of racial and ethnic identities in the US, let alone around the world. Please choose the options that best describes you.
                            </p>
                    </div>
                    </b-collapse>
                </div>
                <div slot="answer-block">
                    <b-form-checkbox-group stacked class="pl-2">
                        <b-form-checkbox v-model="race" :value="option" v-for="option in races" :key="option">
                            {{option}}
                        </b-form-checkbox>
                        <div class="form-inline">
                            <b-form-checkbox v-model="race" :value="100">Other</b-form-checkbox>
                            &nbsp;
                            <b-form-input v-model="raceOther" v-if="race !== null && race.indexOf(100) > -1"></b-form-input>
                        </div>
                    </b-form-checkbox-group>
                </div>
            </question-block>

            <div>
                <button class="btn btn-light" @click="cancelForm">Cancel</button>
                <button class="btn btn-primary" @click="submitDemographics">Submit</button>
            </div>
        </b-modal>
    </div>
</template>
<script>
import {mapMutations, mapGetters, mapActions} from 'vuex'
import updateUserPreference from '../../../resources/users/update_user_preference'

export default {
    props: {
        volunteer: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            formVisible: false,
            selfDesc: null,
            selfDescOther: null,
            selfDescriptions: null, // comes from server
            education: null,
            educationOther: null,
            educationLevels: {
                1: 'Some high school education',
                2: 'High school diploma',
                3: 'Bachelor\'s degree',
                4: 'Master\'s degree',
                5: 'M.D.',
                6: 'Ph.D.',
            },

            race: null,
            raceOther: null,
            races: [
                'American Indian or Alaska Native',
                'Asian',
                'Black or African American',
                'Hispanic or Latino',
                'Native Hawaiian or Other Pacific Islander',
                'White',
            ],
            submitting: false,
            responded: true
        }
    },
    computed: {
        ...mapGetters({
            user: 'getUser'
        }),
        showSelfDesc() {
            return this.volunteer.application.self_desc.rawValue === null;
        },
        showEducation() {
            return this.volunteer.application.highest_ed.rawValue === null;
        },
        showRace() {
            return this.volunteer.application.race_ethnicity.rawValue === null;
        }
    },
    watch: {
        user() {
            if (this.user.isLoaded()) {
                this.responded = (this.user.getPreference('hide_demographic_ad')) ? true : false;
            }
        },
    },
    methods: {
        ...mapMutations([
            'setUser',
            'messages/addInfo'
        ]),
        showForm() {
            this.formVisible = true
        },
        hideForm() {
            this.formVisible = false
        },
        declineDemographics() {
            this.setResponded()
        },
        clearForm() {
            this.selfDesc = null;
            this.selfDescOther = null;
            this.education = null;
            this.educationOther = null;
            this.race = null;
            this.raceOther = null;
        },
        cancelForm() {
            this.$emit('responded')
            this.clearForm()
            this.formVisible = false;
        },
        async setResponded() {
            const newUserData = await updateUserPreference(this.user.id, 'hide_demographic_ad', true);
            this.setUser(newUserData);
        },
        submitDemographics() {
            this.formVisible = false;
            this.submitting = true;
            this['messages/addInfo']('Thanks for sharing your demographic info with us.')
            const data = {
                'self_desc': this.selfDesc,
                'self_desc_other': this.selfDescOther,
                'highest_ed': this.education,
                'highest_ed_other': this.educationOther,
                'race_ethnicity': (this.race === null) ? this.race : JSON.stringify(this.race),
                'race_ethnicity_other_detail': this.raceOther
            };

            if (Object.values(data).filter(val => val !== null).length > 0) {
                this.setResponded();
            }

            window.axios.put('/api/volunteers/'+this.volunteer.id+'/demographics/', data)
                .then(response => {
                    this.submitting = false
                    this.clearForm();
                    this.$emit('updated');
                })
        }
    },
    async mounted() {
        this.selfDescriptions = await window.axios.get('/api/self-descriptions')
                                    .then(response => response.data.data.filter(sd => sd.active == 1 && sd.id != 100));

        this.responded = (this.user.getPreference('hide_demographic_ad') == 1) ? true : false;
    }
}
</script>