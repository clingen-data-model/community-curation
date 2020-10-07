<template>
    <div class="fixed-bottom py-2 bg-white border-top"> 
        <button 
            class="btn btn-light border float-right mr-2"
            :class="'btn-'+buttonSize"
            @click="expanded = !expanded"
        >
            <b-icon :icon="icon"></b-icon
        ></button>
        <div class="container" v-if="expanded">
            <slot></slot>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        
    },
    data() {
        return {
            expanded: true
        }
    },
    computed: {
        buttonSize() {
            return this.expanded ? 'sm' : 'xxs'
        },
        icon() {
            return this.expanded ? 'chevron-down' : 'chevron-up'
        }
    },
    methods: {

    }
}
</script>