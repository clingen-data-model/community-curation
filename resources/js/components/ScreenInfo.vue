<template>
    <div>
        <button class="btn btn-light btn-sm border" @click="toggleModal">{{title}}</button>
        <b-modal :title="title" v-model="showModal">
            <b-embed
                v-if="this.videoUrl"
                type="iframe"
                :src="videoUrl"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen
            ></b-embed>
            <div class="mt-3">
                <slot></slot>
            </div>
        </b-modal>
    </div>
</template>
<script>
import { BEmbed } from 'bootstrap-vue'

export default {
    components: {
        BEmbed
    },
    props: {
        title: {
            type: String,
            required: false,
            default: 'About this screen.'
        },
        videoUrl: {
            type: String,
            required: false,
            default: null
        }
    },
    data() {
        return {
            showModal: false,
        }
    },
    computed: {

    },
    methods: {
        toggleModal() {
            this.showModal = !this.showModal   
        }
    }
}
</script>