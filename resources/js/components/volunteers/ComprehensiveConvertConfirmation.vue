<script>
    import updateVolunteer from '../../resources/volunteers/update_volunteer'

    export default {
        props: {
            volunteer: {
                type: Object,
            },
            value: {
            }
        },
        emits: [
            'saved',
            'input'
        ],
        computed: {
            showVolunteerTypeForm: {
                get() {
                    return this.value
                },
                set (to) {
                    this.$emit('input', to);
                }
            }
        },
        methods: {
            convertVolunteerToComprehensive() {
                updateVolunteer(this.volunteer.id, { volunteer_type_id: 2})
                    .then(() => {
                        this.$emit('saved');
                        this.$emit('input', false)
                    })
            },
        }
    }
</script>
<template>
    <b-modal title="Convert Volunteer to Comprehensive?" hide-footer v-model="showVolunteerTypeForm">
        <p>You are about to convert this volunteer from Baseline to Comprehensive.</p>
        <p>If you confirm the volunteer will be notified and instructed prioritize Curation Activities and Curation Groups.</p>
        <button class="btn btn-default" @click="$emit('input', false)">Cancel</button>
        <button class="btn btn-primary" @click="convertVolunteerToComprehensive">Convert</button>
    </b-modal>
</template>
