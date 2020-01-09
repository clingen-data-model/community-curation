<style></style>

<template>
    <div class="card p-3">
        <h4>
            <document-uploader 
                :volunteer="volunteer" 
                class="float-right"
                v-on:uploaded="$refs.docList.getDocuments()"
            ></document-uploader>
            Documents
        </h4>
        
        <documents-list :volunteer="volunteer" ref="docList"></documents-list>
    </div>
</template>

<script>
    import DocumentUploader from './DocumentUploader';
    import DocumentsList from './DocumentsList';

    export default {
        components: {
            DocumentUploader,
            DocumentsList
        },
        props: {
            volunteer: {
                reqired: true,
                type: Object
            }
        },
        data() {
            return {
            }
        },
        methods: {
        }
    }
</script>