<style scoped>
    .activity-metrics-container>div {
        border: solid 1px #aaa;
        margin-right: 1rem;
        padding: 1rem;
        border-radius: 7px;
        text-align: center;
    }
    .activity-metrics-container > div > .metric {
        font-size: 2rem;
    }
</style>
<template>
    <div class="activity-metrics-container d-flex">
        <div>
            <h3>Logged in this week:</h3>
            <div class="metric">{{sevenDayLogins}}</div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        
    },
    data() {
        return {
            sevenDayLogins: 0
        }
    },
    computed: {

    },
    methods: {

    }
}
</script>