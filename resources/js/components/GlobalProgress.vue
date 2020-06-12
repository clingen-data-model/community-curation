<template>
    <div>
        <b-progress 
            :value="100" 
            :max="100"  
            animated 
            v-show="loading"
            style="position:fixed; top:0; left:0; right:0; border-radius: 0; z-index: 2000"
            height="10px"
        >
        </b-progress>
    </div>
</template>
<script>
export default {
    computed: {
        loading() {
            return this.$store.getters.loading;
        }
    }
}
</script>