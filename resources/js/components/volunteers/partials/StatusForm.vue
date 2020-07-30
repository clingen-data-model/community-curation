

<template>
    <div class="component-container">
        <div class="form-row">
            <label for="volunteer-status-select" class="col-md-3">Volunteer Status</label>
            <div class="col-md-6">
                <select v-model="newStatus" class="form-control form-control-sm">
                    <option v-for="(status, idx) in volunteerStatuses" :value="status" :key="idx">{{status.name}}</option>
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary btn-sm" @click="updateVolunteerStatus">
                    Update Status
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import updateVolunteer from '../../../resources/volunteers/update_volunteer'
    import getAllVolunteerStatuses from '../../../resources/volunteers/get_all_volunteer_statuses'
    import Volunteer from '../../../entities/volunteer';

    export default {
        props: {
            volunteer: {
                required: true,
                type: Volunteer
            }
        },
        data() {
            return {
                newStatus: null,
                volunteerStatuses: [],
            }
        },
        methods: {
            fetchVolunteerStatuses: async function () {
                this.volunteerStatuses = await getAllVolunteerStatuses();
            },
            updateVolunteerStatus() {
                let confirmationMessage = 'Are you sure you want to update the volunteer\'s status?';
                switch (this.newStatus.name) {
                    case this.volunteer.volunteer_status.name:
                        this.$emit('no-change')
                        return;
                        break;
                    case 'Retired':
                        confirmationMessage = 'You are about to retire this volunteer.  This will also retire all of their assignments.  Are you sure you want to continue?'
                        break;
                    default:
                        break;
                }
                if (confirm(confirmationMessage)) {
                    updateVolunteer(
                        this.volunteer.id, 
                        {
                            'volunteer_status_id': this.newStatus.id
                        }
                    ).then(response => {
                        this.$emit('updatevolunteer')
                    })
                }
            },
        },
        mounted() {
            this.fetchVolunteerStatuses();
            this.newStatus = this.volunteer.volunteer_status
        }
    }
</script>