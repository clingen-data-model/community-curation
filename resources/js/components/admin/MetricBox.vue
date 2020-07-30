<style scoped>
    .metric-box {
        border: solid 2px #aaa;
        margin-right: 1rem;
        padding: 1rem;
        border-radius: 7px;
        text-align: center;
    }
    .metric-box>h3 {
        font-size: 2rem;
        margin: 0;
    }
    .metric {
        font-size: 1.8rem;
        font-weight: 900;
    }
</style>
<template>
    <div class="metric-box">
        <h3 scope="heading">{{title}}</h3>
        <div class="metric">{{value}}</div>
    </div>
</template>
<script>
export default {
    props: {
        title: {
            type: String,
            required: false
        },
        value: {
            required: true
        }
    },
}
</script>