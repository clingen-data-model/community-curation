<style></style>

<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <assignment-card 
                    :volunteer="volunteer"
                    v-on:updatevolunteer="$emit('updatevolunteer')"
                ></assignment-card>
            </div>

            <div class="col-md-6">
                <basic-info-card :volunteer="volunteer"
                    v-on:updatevolunteer="$emit('updatevolunteer', $event)"
                ></basic-info-card>
            </div>
        </div>
    </div>
</template>

<script>
    import AssignmentCard from './AssignmentCard'
    import BasicInfoCard from './BasicInfoCard'

    export default {
        components: {
            AssignmentCard, 
            BasicInfoCard,
        },
        props: {
            volunteer: {
                reqired: true,
                type: Object
            }
        }
    }
</script>