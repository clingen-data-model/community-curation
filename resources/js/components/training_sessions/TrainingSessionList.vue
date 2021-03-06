<template>
    <div>
        <div class="d-flex justify-content-between alert alert-info">
            We've added a video showing you how to use this page: 
            <screen-info
                video-url="https://www.youtube.com/embed/WFFd6f-KaJA" 
                title="Learn about Training Sessions"
            ></screen-info>            
        </div>
        <div class="card">
            <div class="card-header">
                <button class="float-right btn btn-sm btn-primary" @click="showCreate = true">Add new</button>
                <h3 class="m-0">
                    Training Sessions
                </h3>
            </div>
            <div class="card-body">
                <b-tabs pills v-model="selectedTab">
                    <b-tab>
                        <template v-slot:title>
                            Upcoming <b-badge class="ml-1" variant="light">{{sessions.length}}</b-badge>
                        </template>
                        <b-table :fields="fields" :items="sessions" class="mt-4 cursor-pointer"
                            :sort-by.sync="sortBy"
                            :sort-desc.sync="sortDesc"
                            @row-clicked="navigateToTrainingSession"
                        >
                            <template v-slot:cell(id)="{value}">
                                <div class="text-right">
                                    <a :href="`/training-sessions/${value}`" class="btn border btn-light btn-xs">View</a>
                                    <button class="btn border btn-light btn-xs" @click="edit(value)">Edit</button>
                                    <button class="btn border btn-light btn-xs" @click="destroy(value)">Delete</button>
                                </div>
                            </template>
                            <template v-slot:cell(starts_at)="{item}">
                                {{item.starts_at.format('MMMM D, YYYY - h:mm a')}}
                            </template>
                        </b-table>        
                    </b-tab>
                    <b-tab title="Past">
                        <template v-slot:title>
                            Past <b-badge class="ml-1" variant="light">{{pastSessions.length}}</b-badge>
                        </template>
                        <b-table :fields="fields" :items="pastSessions" class="mt-4"
                            :sort-by.sync="pastSortBy"
                            :sort-desc.sync="pastSortDesc"
                            @row-clicked="navigateToTrainingSession"
                            hover
                        >
                            <template v-slot:cell(starts_at)="{item}">
                                {{item.starts_at.format('MMMM D, YYYY - h:mm a')}}
                            </template>
                            <template v-slot:cell(id)="{value}">
                                <div class="text-right">
                                    <a :href="`/training-sessions/${value}`" class="btn border btn-light btn-xs">View</a>
                                    <button class="btn border btn-light btn-xs" @click="edit(value)">Edit</button>
                                    <button class="btn border btn-light btn-xs" @click="destroy(value)">Delete</button>
                                </div>
                            </template>
                        </b-table>        
                    </b-tab>
                </b-tabs>
            </div>
        </div>
        
        <b-modal v-model="showCreate" size="lg" hide-footer>
            <training-session-form
                :errors="errors"
                @saved="createSession"
                @canceled="cancelCreate"
            ></training-session-form>
        </b-modal>
        <b-modal v-model="showEdit" size="lg" hide-footer>
            <training-session-form :training-session="currentSession"
                :errors="errors"
                @saved="updateSession"
                @canceled="cancelUpdate"
            ></training-session-form>
        </b-modal>
    </div>
</template>
<script>
import getTrainingSessions from '../../resources/training_sessions/get_training_sessions';
import destroyTrainingSession from '../../resources/training_sessions/destroy';
import createTrainingSession from '../../resources/training_sessions/create';
import updateTrainingSession from '../../resources/training_sessions/update';

import TrainingSessionForm from '../training_sessions/TrainingSessionForm'
import TrainingSession from '../../entities/training_session'

export default {
    components: {
        TrainingSessionForm
    },
    data() {
        return {
            selectedTab: this.getSelectedTab(),
            sessions: [],
            pastSessions: [],
            fields: [
                {
                    key: 'topic.name',
                    label: 'Topic',
                    sortable: true,
                },
                {
                    key: 'starts_at',
                    label: 'Date & Time',
                    sortable: 'true',
                    formatter: (value, key, item) => this.$options.filters.formatDate(value, 'YYYY-MM-DD HH:mm')+' EST',
                    sortByFormatted: true
                },
                {
                    key: 'id',
                    label: '',
                    sortable: false
                }
            ],
            sortBy: 'starts_at',
            sortDesc: false,
            pastSortBy: 'starts_at',
            pastSortDesc: true,
            showCreate: false,
            showEdit: false,
            currentSession: {},
            errors: {},
        }
    },
    computed: {
        allSessions: function () {
            return this.sessions.concat(this.pastSessions);
        }
    },
    watch: {
        selectedTab: function (to, from) {
            if (to != from) {
                localStorage.setItem('training-list-tab', this.selectedTab);
            }            
        }
    },
    methods: {
        navigateToTrainingSession (trainingSession) {
            window.location = '/training-sessions/'+trainingSession.id
        },
        edit(id) {
            this.currentSession = this.allSessions.find(ts => ts.id == id);
            this.showEdit = true;
        },
        destroy(id) {
            if (confirm('You are about to delete this training session.  This cannot be undone and the attendees will be notified of its cancellation.  Do you want to continue?')) {
                destroyTrainingSession(id)
                    .then(response => {
                        this.getSessions();
                        this.getPastSessions();
                    })
            }
        },
        async getSessions() {
            this.sessions = await getTrainingSessions();
        },
        async getPastSessions() {
            this.pastSessions = await getTrainingSessions({
                scopes: ['past']
            });
        },
        getSelectedTab() {
            let tabIdx = localStorage.getItem('training-list-tab');
            if (tabIdx !== null) {
                return parseInt(tabIdx);
            }
            return 0;
        },
        createSession(data) {
            this.errors = {};
            createTrainingSession(data)
                .then(response => {
                    let session = new TrainingSession(response.data.data);
                    this.sessions.push(session);
                    this.currentSession = {};
                    this.showCreate = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors
                })
        },
        cancelCreate() {
            this.errors = {};
            this.showCreate = false;
        },
        updateSession(trainingSession) {
            this.errors = {};
            updateTrainingSession(this.currentSession.id, trainingSession)
                .then(response => {
                    this.currentSession = {};
                    this.showEdit = false;

                    this.getSessions();
                    this.getPastSessions();
                })
                .catch(error => {
                    this.errors = error.response.data.errors
                })
        },
        cancelUpdate() {
            this.errors = {};
            this.currentSession = {};
            this.showEdit = false;
        }
    },
    mounted() {
        this.getSessions();
        this.getPastSessions();
        window.addEventListener('popstate', (e) => {
            this.selectedTab = e.tabIdx
        });
    }
}
</script>