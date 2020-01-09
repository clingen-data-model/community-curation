<style></style>

<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <assignment-card 
                    :volunteer="volunteer"
                    v-on:updatevolunteer="$emit('updatevolunteer')"
                ></assignment-card>
                <!-- <div class="card p-3 mt-4">
                    <div class="card-hearder">
                        <h4>Aptitudes &amp; trainings</h4>
                    </div>
                </div> -->
            </div>

            <div class="col-md-6">
                <contact-info-card :volunteer="volunteer"></contact-info-card>
                <documents-card :volunteer="volunteer"></documents-card>
            </div>
        </div>
    </div>
</template>

<script>
    import AssignmentCard from './AssignmentCard'
    import ContactInfoCard from './ContactInfoCard'
    import DocumentsCard from './DocumentsCard'

    export default {
        components: {
            AssignmentCard, 
            ContactInfoCard,
            DocumentsCard
        },
        props: {
            volunteer: {
                reqired: true,
                type: Object
            }
        }
    }
</script>