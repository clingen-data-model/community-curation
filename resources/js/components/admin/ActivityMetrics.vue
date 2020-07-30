<style scoped>
    .activity-metrics-container>div {
        border: solid 2px #aaa;
        margin-right: 1rem;
        padding: 1rem;
        border-radius: 7px;
        text-align: center;
    }
    .activity-metrics-container > div > .metric {
        font-size: 2rem;
        font-weight: 900;
    }
</style>
<template>
    <div>
        <div class="activity-metrics-container d-flex justify-items-between">
            <div>
                <h3>Logged in last 7 days</h3>
                <div class="metric">{{sevenDayLogins}}</div>
            </div>
            <div>
                <h3>Logged in last 30 days</h3>
                <div class="metric">{{sevenDayLogins}}</div>
            </div>
            <div>
                <h3>Never logged in</h3>
                <div class="metric">{{neverLoggedIn}}</div>
            </div>
            <!-- <div>
                <h3>Applications completed in last 7 days</h3>
                <div class="metric">Coming soon</div>
            </div> -->
        </div>
    </div>
</template>
<script>
import getUsers from '../../resources/users/get_users'
import moment from 'moment'

export default {
    props: {
        
    },
    data() {
        return {
            sevenDayLogins: 0,
            thirtyDayLogins: 0,
            neverLoggedIn: 0,
        }
    },
    computed: {

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
            const val =  await getUsers({'last_logged_in_at': moment().subtract(quantity, unit).format('YYYY-MM-DD HH:mm:ss')})
                .then(response => {
                    return response.data.data.length
                });

            console.log(val);
            return val;
        },
        getMetrics () {
            this.getSevenDayLogins();
            this.getThirtyDayLogins();
            this.getNeverLoggedIn();
        }
    },
    mounted() {
        this.getMetrics()
    }
}
</script>