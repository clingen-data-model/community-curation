<template>
    <div>
        <table class="table table-striped">
            <tr>
                <th>Topic</th>
                <th>Date Completed</th>
            </tr>
            <tr v-for="(userAptitude, idx) in trainings" :key="idx">
                <td>{{userAptitude.aptitude.name}}</td>
                <td>
                    <span v-if="userAptitude.trained_at">{{userAptitude.trained_at | formatDate('YYYY-MM-DD')}}</span>
                    <span class="text-muted" v-else>incomplete</span>
                </td>
            </tr>
            <tr v-if="trainings.length == 0">
                <td colspan="2">
                    <div class="alert alert-secondary text-center">No trainings assigned.</div>
                </td>
            </tr>
        </table>
        <div class="alert alert-light border">
            Note that this list currently includes only trainings and completed dates related directly to assignments and aptitudes, it does not track individual training sessions.
        </div>
    </div>
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
        trainings: function () {
            return this.volunteer.assignments.map(asn => asn.user_aptitudes.items).flat();
        }
    },
    methods: {

    }
}
</script>