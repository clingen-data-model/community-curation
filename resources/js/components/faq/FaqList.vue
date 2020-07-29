<template>
        <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header">
                    <div class="float-right" v-if="canEdit"><a href="admin/faq" class="btn btn-primary border btn-sm">Manage FAQs</a></div>
                    <h3>Frequently Asked Questions</h3>
                </div>
                <div class="card-body">
                    <dl>
                        <div v-for="faq in faqs" :key="faq.id">
                            <dt>
                                <div class="float-right" v-if="canEdit">
                                    <a :href="`admin/faq/${faq.id}/edit`" class="btn btn-default border btn-xs">
                                        <b-icon-pencil></b-icon-pencil>
                                    </a>
                                </div>
                                <!-- v-b-toggle.faq-collapse-{{faq.id}} -->
                                <h5>{{faq.question}}</h5>
                            </dt>
                            <dd class="mb-4 pb-3 pl-3 border-bottom" style="font-size: 1rem;">
                                <!-- <b-collapse :id="`faq-collapse-${faq->id}`" class="clearfix"> -->
                                    {{faq.answer}}
                                    <div
                                        v-if="faq.screenshots"
                                        class="d-flex align-items-start flex-wrap w-100"
                                    >
                                        <div v-for="path in faq.screenshots" 
                                            :key="path" 
                                            style="width: 30%; margin-right: 3%; margin-bottom: 1rem; border: 1px solid #777"
                                            @click="showFullsizeImage(path)"
                                        >
                                            <img :src="`${path}`" class="w-100"/>
                                        </div>
                                    </div>
                                <!-- </b-collapse> -->
                            </dd>
                        </div>
                        <b-modal hide-header hide-footer v-model="showFullsizeModal" :size="modalSize">
                            <img :src="`${currentScreenshot}`" class="w-100 border" />
                        </b-modal>
                    </dl>
                
                    <p style="font-size: 1.25rem">
                        If you have any questions not addressed on this FAQ, please email <a href="mailto:volunteer@clinicalgenome.org">volunteer@clinicalgenome.org</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
export default {
    props: {
        faqs: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            currentScreenshot: {},
            showFullsizeModal: false,
            currentImage: {}
        }
    },
    computed: {
        canEdit(){
            // @if (\Auth::user() && \Auth::user()->hasAnyPermission('create faq', 'update faq'))

            return this.$store.state.user.isAdmin() || this.$store.state.user.isProgrammer();
        },
        modalSize(){
            console.info('this.currentImage.width', this.currentImage.width);
            if (this.currentImage.width > 800) {
                return 'xl';
            }

            if (this.currentImage.width > 500) {
                return 'lg';
            }

            if (this.currentImage.width > 300) {
                return 'md';
            }

            return 'sm';
        }
    },
    methods: {
        showFullsizeImage(path) {
            const img = new Image();
            img.src = '/storage/'+path;
            this.currentImage = img;
            this.currentScreenshot = path;
            this.showFullsizeModal = true;
        }
    }
}
</script>