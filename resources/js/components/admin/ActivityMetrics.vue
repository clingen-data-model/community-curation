<template>
    <div>
        <div class="activity-metrics-container d-flex">
            <metric-box title="New applications in last 7 days" :value="sevenDayApplications"></metric-box>
            <metric-box title="New applications in last 30 days" :value="thirtyDayApplications"></metric-box>
            <metric-box title="Logged in last 7 days" :value="sevenDayLogins"></metric-box>
            <metric-box title="Logged in last 30 days" :value="thirtyDayLogins"></metric-box>
            <metric-box title="Never logged in" :value="neverLoggedIn"></metric-box>
        </div>
        <div class="note text-muted" v-if="now < aug6"><small>* 7 day login numbers will not be accurate until Aug. 6</small></div>
        <div class="note text-muted" v-if="now < aug29"><small>* 30 day login numbers will not be accurate until Aug. 29</small></div>
    </div>
</template>
<script>
import getUsers from '../../resources/users/get_users'
import moment from 'moment-timezone'
import MetricBox from './MetricBox'

export default {
    components: {
        MetricBox
    },
    data() {
        return {
            sevenDayLogins: null,
            thirtyDayLogins: null,
            neverLoggedIn: null,
            sevenDayApplications: null,
            thirtyDayApplications: null,
            now: new Date(),
            aug6: new Date(2020,8,6),
            aug29: new Date(2020,8,29)
        }
    },
    methods: {
        async getNeverLoggedIn() {
            this.neverLoggedIn = await getUsers({'last_logged_in_at': 0}).then(rsp => rsp.data.data.length);
        },
        async getSevenDayLogins() {
            this.sevenDayLogins = await this.getLoginsInLast(7, 'day');
        },
        async getThirtyDayLogins() {
            this.thirtyDayLogins = await this.getLoginsInLast(30, 'day');
        },
        async getLoginsInLast(quantity, unit)
        {
            const val =  await getUsers({'last_logged_in_at': moment().subtract(quantity, unit).utc().format('YYYY-MM-DD HH:mm:ss')})
                .then(response => {
                    return response.data.data.length
                });

            return val;
        },
        async getSevenDayApplications()
        {
            this.sevenDayApplications = await this.getApplicationsInLast(7, 'day');
        },
        async getThirtyDayApplications()
        {
            this.thirtyDayApplications = await this.getApplicationsInLast(30, 'day');
        },
        async getApplicationsInLast(quantity, unit)
        {
            const val = await window.axios.get(`/api/applicaitons?finalized_at=${moment().subtract(quantity, unit).utc().format('YYYY-MM-DD HH:mm:ss')}&only_count=1`)
                            .then(response => response.data.data);
            return val;
        },
        getMetrics () {
            this.getSevenDayLogins();
            this.getThirtyDayLogins();
            this.getSevenDayApplications();
            this.getThirtyDayApplications();
            this.getNeverLoggedIn();
            
        }
    },
    mounted() {
        this.getMetrics()
    }
}
</script>