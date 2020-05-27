<template>
    <only-volunteer v-if="showReminder">
        <div class="alert alert-warning mt-0 mx-auto">
            <h3>Do you have a <hypothesis-link></hypothesis-link> account?</h3>
            If so, please <a href="#basic-form" @click.prevent="$emit('share_hypothesis')"><strong>share it with us</strong></a> so we can add you to ClinGen groups; 
            if not, please <a href="https://web.hypothes.is/start/" target="hypothes.is"><strong>create one</strong></a> and share it with us.
            <p>
                We use <hypothesis-link></hypothesis-link>, a free and open, web-based annotation tool, to aid our biocuration efforts. We need your hypothes.is username so we can add you to groups and keep track of your contribution.
            </p>
        </div>
    </only-volunteer>
</template>
<script>
export default {
    props: {
        volunteer: {
            required: true,
            type: Object
        }
    },
    computed: {
        showReminder: function () {
            return this.volunteer.hypothesis_id === null 
                    && this.volunteer.isBaseline();
                    // && this.$store.state.user.isVolunteer();
        }
    },
    methods: {

    }
}
</script>