<template>
    <div>
        <!-- <pre>{{errors}}</pre> -->
        <div class="form-group row">
            <label for="topic" class="col-sm-3">Topic *</label>
            <div class="col-sm-9">
                <select v-model="newSessionData.topic_id" id="topic" class="form-control">
                    <option value="">Select&hellip;</option>
                    <option :value="topic.id" v-for="topic in topics" :key="topic.id">{{topic.name}}</option>
                </select>
                <validation-error :errors="errors.topic_id"></validation-error>
            </div>
        </div>
        <div class="form-group row">
            <label for="start-date-field" class="col-sm-3">
                Start Date &amp; time*
            </label>
            <div class="col-sm-9">
                <date-time v-model="newSessionData.starts_at" class="form-inline" @change="updateEndsAt"></date-time>
                <validation-error :errors="errors.starts_at"></validation-error>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3" for="duration-field">Duration (in minutes)*</label>
            <div class="col-sm-9">
                <input type="text" v-model="duration" id="duration-field" class="form-control" @change="updateEndsAt">
                <small class="text-muted">Ends {{this.newSessionData.ends_at | formatDate('YYYY-MM-DD hh:mm A')}}</small>
                <validation-error :errors="durationErrors"></validation-error>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3" for="url-field">URL*</label>
            <div class="col-sm-9">
                <input type="text" id="url-field" v-model="newSessionData.url" class="form-control w-100" placeholder="https://zoom.com">
                <validation-error :errors="errors.url"></validation-error>
            </div>
        </div>
        <div class="form-group row align-items-start">
            <label class="col-sm-3" for="invie-message-field">Inivite Message</label>
            <div class="col-sm-9">
                <rich-text-editor v-model="newSessionData.invite_message" id="message-body"></rich-text-editor>
                <validation-error :errors="errors.invite_message"></validation-error>
            </div>
        </div>
        <div class="form-group row align-items-start">
            <label class="col-sm-3" for="notes-field">Notes</label>
            <div class="col-sm-9">
                <textarea id="nots-field"
                    rows="5"
                    v-model="newSessionData.notes"
                    class="form-control w-100"
                ></textarea>
                <validation-error :errors="errors.notes"></validation-error>
            </div>
        </div>
        <div>
            <button class="btn btn-default border" @click="$emit('canceled')">
                Cancel
            </button>
            <button class="btn btn-primary" @click="$emit('saved', newSessionData)">
                Save
            </button>
        </div>
    </div>
</template>
<script>
import moment from 'moment'
import TrainingSession from '../../entities/training_session'
import DateTime from '../DateTime'
import getAllCurationActivities from '../../resources/curation_activities/get_all_curation_activities'
import getBaselineActivities from '../../resources/curation_activities/getBaselineActivities'

export default {
    components: {
        DateTime
    },
    props: {
        trainingSession: {
            type: Object,
            required: false,
            default: () => new TrainingSession()
        },
        errors: {
            type: Object,
            default: () => {},
        }
    },
    data() {
        return {
            topics: [{}],
            newSessionData: {},
            duration: 60,
            durationMin: 15,
            durationMax: 240,
            durationErrors: [],
            newInviteMessage: '',
        }
    },
    computed: {
        currentTimezone() {
            return Intl.DateTimeFormat().resolvedOptions().timeZone;
        }
    },
    methods: {
        updateEndsAt() {
            if (this.newSessionData.starts_at && this.duration) {
                this.newSessionData.ends_at = this.newSessionData.starts_at.clone().add(this.duration, 'minutes');
            }            

        },
        initNewSession() {
            if (this.trainingSession !== null) {
                this.syncTrainingSession();
            }
        },
        syncTrainingSession() {
            this.newSessionData = this.trainingSession.attributes;
            this.duration = this.trainingSession.duration ? this.trainingSession.duration : 60;
        },
        durationIsValid() {
            if (this.duration < this.durationMin || this.duration > this.durationMax) {
                this.durationErrors = ['The session must be at least 15 minutes and no more than 240 minutes.'];
                return false;
            }
            return true;
        },
        loadCurationActivities() {
            this.topics = [];
            getAllCurationActivities()
                .then(response => {
                    response.forEach(item => {
                        this.topics.push(item);
                    })
                });
            getBaselineActivities()
                .then(response => {
                    response.forEach(item => {
                        this.topics.push(item);
                    })
                });
        }
    },
    mounted() {
        this.initNewSession();
        this.loadCurationActivities();
    }
}
</script>