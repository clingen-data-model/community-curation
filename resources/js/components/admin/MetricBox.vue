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