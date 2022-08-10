<template>
    <div>
        <button class="btn btn-light btn-sm border" @click="showPreview">Preview invite email</button>
        <b-modal v-model="showPreviewModal" title="Invite Preview" hide-footer>
            <div>
                    <p>Hi [VOLUNTEER NAME],</p>

                    <p>
                        This email is to inform you that a live training session for {{topic.name}} will be held on 
                        {{startsAt}}.
                    </p>
                    
                    <p>Attendance for this live training session is required for volunteer training completion and curation assignment.</p>
                    
                    <blockquote v-html="inviteMessage">
                    </blockquote>

                    <p>If you have any questions or concerns, please contact us at <a href="mailto:volunteer@clinicalgenome.org">volunteer@clinicalgenome.org</a>.</p>
                    
                    <p>Thank you for your participation,</p>
                    
                    <p>The ClinGen Community Curation (C3) Working Group.</p>

                    <br>
                    Add the training session to your calendar:
                    <ul>
                        <li v-for="link in ['Google', 'Web-based Outlook', 'Yahoo']" :key="link">
                            <a href="#">{{link}}</a>
                        </li>
                    </ul>                
            </div>
        </b-modal>
    </div>
</template>
<script>
export default {
    props: {
        trainingSession: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            showPreviewModal: false,
            previewMarkup: null
        }
    },
    computed: {
        topic() {
            if (!this.trainingSession.topic || !this.trainingSession.topic.name) {
                return {'name': '[TOPIC NAME]'};
            }
            return this.trainingSession.topic
        },
        startsAt() {
            return moment(this.trainingSession.starts_at).isValid()
                    ? moment(this.trainingSession.starts_at).format('dddd, MMMM DD, YYYY [at] hh:mm a') 
                    : '[START DATE at TIME]'
        },
        inviteMessage() {
            return this.trainingSession.invite_message || '[YOUR INVITE MESSAGE]';
        }
    },
    methods: {
        showPreview() {
            this.showPreviewModal = true;
            if (this.trainingSession.id) {
                this.getPreview();
            }
        },
        async getPreview()
        {
            this.previewMarkup = await window.axios.get('/api/training-sessions/'+this.trainingSession.id+'/invite-preview')
                .then(response => response.data);
        }
    }
}
</script>