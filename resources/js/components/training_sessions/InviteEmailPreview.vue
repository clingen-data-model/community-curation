<template>
  <div>
    <button class="btn btn-light btn-sm border" @click="showPreview">Preview invite email</button>
    <b-modal v-model="showPreviewModal" title="Invite Preview" hide-footer>
      <div v-if="isLoading">Loading preview…</div>
      <div v-else-if="previewMarkup" v-html="previewMarkup"></div>
      <div v-else>
        <p>Hi [VOLUNTEER NAME],</p>

        <p>
          This email is to inform you that a live training session for {{topic.name}} will be held on {{startsAt}}.
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
      previewMarkup: null,
      isLoading: false,
    }
  },
  computed: {
    topic() {
      if (!this.trainingSession.topic || !this.trainingSession.topic.name) {
        return { name: '[TOPIC NAME]' };
      }
      return this.trainingSession.topic;
    },
    startsAt() {
      return moment(this.trainingSession.starts_at).isValid()
        ? moment(this.trainingSession.starts_at).format('dddd, MMMM DD, YYYY [at] hh:mm a z')
        : '[START DATE at TIME]';
    },
    inviteMessage() {
      return this.trainingSession.invite_message || '[YOUR INVITE MESSAGE]';
    }
  },
  methods: {
    showPreview() {
      this.showPreviewModal = true;
      this.previewMarkup = null;
      if (this.trainingSession.id) {
        this.getPreview();
      }
    },
    async getPreview() 
    {
      this.isLoading = true;
      try {
        const resp = await window.axios.get(
          '/api/training-sessions/' + this.trainingSession.id + '/invite-preview',
          { responseType: 'text' } 
        );
        this.previewMarkup = resp.data;
      } finally {
        this.isLoading = false;
      }
    }
  }
}
</script>