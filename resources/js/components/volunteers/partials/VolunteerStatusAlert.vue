

<template>
    <div class="component-container">
        <div class="alert alert-danger" v-if="hasDangerStatus">
            <button class="float-right btn btn-light btn-sm" @click="$emit('updatestatus')">Update Status</button>
            <strong>Status Notice:</strong>
            This volunteer has been marked
            <strong>{{volunteer.volunteer_status.name}}</strong>
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
            hasDangerStatus: function () {
                return ['retired', 'unresponsive', 'declined']
                            .indexOf(this.volunteer.volunteer_status.name.toLowerCase()) > -1
            }
        }
    }
</script>