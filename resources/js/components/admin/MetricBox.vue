<template>
    <div class="metric-box mx-3">
        <h5 scope="heading">{{title}}</h5>
        <div class="metric text-center lead">
            {{value}}
        </div>
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