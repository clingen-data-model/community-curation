<template>
    <div>
        <div class="card">
            <div class="card-header">
                <div class="float-right ">
                    <button class="btn btn-sm btn-primary" @click="addToCalendar">Add to Calendar</button>
                    <button class="btn btn-sm btn-primary" @click="showEdit = true">Edit</button>
                </div>
                <h3 class="m-0">
                    Training Session
                    -
                    {{trainingSession.topic.name}}
                    on
                    {{trainingSession.starts_at | formatDate('YYYY-MM-DD \\at h:mm a')}}
                </h3>
            </div>
            <div class="card-body">
                <div v-if="show404" class="alert alert-warning">
                    The session you're looking for could not be found.
                </div>
                <section id="session-info">
                    <h4>Session Info</h4>
                    <row class="mb-2">
                        <column class="col-md-2 text-right">
                            <strong>URL</strong>
                        </column>
                        <column class="col-md-9">
                            {{trainingSession.url}}
                        </column>
                    </row>
                    <row class="mb-2">
                        <column class="col-md-2 text-right">
                            <strong>Invite Message</strong>
                        </column>
                        <column class="col-md-9">
                            {{trainingSession.invite_message}}
                        </column>
                    </row>
                    <row class="mb-2">
                        <column class="col-md-2 text-right"><strong>Notes</strong></column>
                        <column class="col-md-9">{{trainingSession.notes}}</column>
                    </row>
                </section>
                <section id="attendees" class="mt-5">
                    <attendees-manager :training-session="trainingSession"></attendees-manager>
                </section>
            </div>
        </div>
        <b-modal v-model="showEdit" size="lg" hide-footer>
            <training-session-form :training-session="trainingSession"
                :errors="errors"
                @saved="updateSession"
                @canceled="cancelUpdate"
            ></training-session-form>
        </b-modal>
    </div>
</template>
<script>
import updateTrainingSession from '../../resources/training_sessions/update';
import TrainingSessionForm from './TrainingSessionForm'
import AttendeesManager from './AttendeesManager'
import moment from 'moment'

export default {
    components: {
        TrainingSessionForm,
        AttendeesManager
    },
    props: {
        id: {
            required: true,
            type: Number
        }
    },
    data() {
        return {
            trainingSession: {
                topic: {}
            },
            showEdit: false,
            errors: {},
            show404: false,
        }
    },
    methods: {
        updateSession(trainingSession) {
            this.errors = {};
            updateTrainingSession(this.currentSession.id, trainingSession)
                .then(response => {
                    this.currentSession = {};
                    this.showEdit = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors
                })
        },
        cancelUpdate() {
            this.errors = {};
            this.currentSession = {};
            this.showEdit = false;
        },
        addToCalendar() {
            alert('add to calendar')
        },
        async getTrainingSession ()
        {
            this.show404 = false;
            this.trainingSession = await window.axios.get('/api/training-sessions/'+this.id)
                                    .then(response => {
                                        let ses = response.data;
                                        ses.starts_at = moment(ses.starts_at)
                                        ses.starts_at = moment(ses.ends_at)
                                        return ses
                                    })
                                    .catch(error => {
                                        if (error.response.status == 404) {
                                            this.show404 = true;
                                            return
                                        }
                                        new Error(error);
                                    })
        }
    },
    mounted() {
        this.getTrainingSession();
    }
}
</script>