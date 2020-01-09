<style></style>

<template>
    <div>
        <div class="alert alert-info" v-if="loadingDocuments">Loading...</div>
        <div class="alert alert-light border" v-if="!loadingDocuments && documents.length == 0">
            No documents found
        </div>
        <table class="table table-sm table-striped" v-if="!loadingDocuments && documents.length > 0">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Created</th>
                <th>&nbsp;</th>
            </tr>
            <template v-for="document in documents">
                <tr :key="document.id">
                    <td>{{document.id}}</td>
                    <td>{{document.name}}</td>
                    <td>{{document.category ? document.category.name : ''}}</td>
                    <td>{{document.created_at | formatDate('YYYY-MM-DD')}}</td>
                    <td>
                        <a href="#" @click.prevent="downloadFile(document)">
                            <i class="material-icons">cloud_download</i>
                        </a>
                        <a href="#" @click.prevent="showDetails(document)">
                            <i class="material-icons">info</i>
                        </a>
                    </td>
                </tr>
            </template>
        </table>
        <b-modal v-model="showDetailedInfo" hide-footer v-if="currentDocument" :title="currentDocument.name">
            <div class="row">
                <div class="col-sm-2">Category:</div>
                <div class="col-sm-10">{{currentDocument.category ? currentDocument.category.name : '--'}}</div>
            </div>
            <div class="row">
                <div class="col-sm-2">Notes:</div>
                <div class="col-sm-10">{{currentDocument.notes ? currentDocument.notes : '--'}}</div>
            </div>
            <div class="mt-2">
                <button class="btn btn-primary text-middle btn-sm">
                    Download document
                </button>
            </div>
        </b-modal>
    </div>
</template>

<script>
    import DocumentUploader from './DocumentUploader'

    export default {
        components: {
            DocumentUploader
        },
        props: {
            volunteer: {
                reqired: true,
                type: Object
            }
        },
        data() {
            return {
                showDetailedInfo: false,
                loadingDocuments: false,
                documents: [],
                currentDocument: null
            }
        },
        methods: {
            getDocuments() {
                this.loadingDocuments = true;
                axios.get('/api/curator-uploads?with[]=category&where[user_id]='+this.volunteer.id)
                    .then(response => {
                        this.documents = response.data.data;
                    })
                    .then(() => this.loadingDocuments = false)
            },
            showDetails(document) {
                console.log('farts')
                this.currentDocument = document
                this.showDetailedInfo = true;
            },
            downloadFile(document) {
                axios.get('/api/curator-uploads/'+document.id+'/file',
                    {
                        responseType: 'blob'
                    })
                    .then(response => {
                        console.log(response.data);
                        const data  = response.data;
                        let a = window.document.createElement('a');
                        let url = window.URL.createObjectURL(data);
                        a.href = url;
                        a.download = document.name;
                        window.document.body.append(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);

                    })
                    .catch(error => {
                        if (error.response && error.response.status == 404) {
                            alert('We couldn\'t seem to find the file you requested.');
                            return;
                        }

                        throw error;
                    })
            }
        },
        mounted() {
            this.getDocuments();
        }
    
}
</script>