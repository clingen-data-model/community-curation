<template>
    <div>
        <button class="btn btn-light btn-sm border" @click="showPreview">Preview invite email</button>
        <b-modal v-model="showPreviewModal" title="Invite Preview" hide-footer>
            <div v-show="!previewMarkup">
                Loading the preview...
            </div>
            <div v-show="previewMarkup" v-html="previewMarkup"></div>
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

    },
    methods: {
        showPreview() {
            this.showPreviewModal = true;
            window.axios.get('/api/training-sessions/'+this.trainingSession.id+'/invite-preview')
                .then(response => this.previewMarkup = response.data);
            //get preview
        }
    }
}
</script>