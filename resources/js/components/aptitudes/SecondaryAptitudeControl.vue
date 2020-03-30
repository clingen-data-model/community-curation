<template>
    <div>
        <div v-if="assignment.assignable.aptitudes.length > 1"
            class="ml-1"
        >
            <div v-if="assignment.user_aptitudes.pending().secondary().length() > 0">
                <training-and-attestation-control
                    :userAptitude="assignment.user_aptitudes.pending().secondary().get(0)"
                    v-on:trainingcompleted="$emit('trainingcompleted', $event)"
                ></training-and-attestation-control>
            </div>
            <ul class="list-unstyled mt-1" v-else>
                <li v-for="apt in assignment.getUnassignedAptitudes()" :key="apt.id">
                    <non-volunteer>
                        <button class="btn btn-sm btn-link"
                            @click="$emit('assignAptitude', apt)"
                        >
                            Assign {{apt.name}}
                        </button>
                    </non-volunteer>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
    import TrainingAndAttestationControl from '../assignments/TrainingAndAttestationControl'

    export default {
        components: {
            TrainingAndAttestationControl
        },
        props: {
            assignment: {
                type: Object,
                required: true
            }
        },
    }
</script>