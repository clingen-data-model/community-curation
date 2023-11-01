<template>
    <div>
        <div v-if="assignment.assignable.aptitudes.length > 1"
            class="ml-1"
        >
            <div v-if="assignment.user_aptitudes.pending().secondary().length > 0">
                <training-and-attestation-control
                    :userAptitude="assignment.user_aptitudes.pending().secondary().get(0)"
                    v-on:trainingcompleted="$emit('trainingcompleted', $event)"
                ></training-and-attestation-control>
            </div>
            <div v-else>
                <div v-if="assignment.user_aptitudes.granted().secondary().length > 0">
                    <ul class="list-unstyled">
                        <li v-for="userApt in assignment.user_aptitudes.granted().secondary()" :key="userApt.id">
                            <small>{{userApt.aptitude.name}}</small>
                        </li>
                    </ul>
                    {{assignment.user_aptitudes.g}}
                </div>
                <ul class="list-unstyled mt-1" v-else>
                    <li v-for="apt in assignment.getUnassignedAptitudes().secondary()" :key="apt.id">
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
    </div>
</template>
<script>
    import TrainingAndAttestationControl from '../assignments/TrainingAndAttestationControl.vue'

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