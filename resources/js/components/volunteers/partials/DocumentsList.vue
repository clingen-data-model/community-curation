

<template>
    <div>
        <div class="alert alert-info" v-if="loadingDocuments">Loading...</div>
        <div class="alert alert-light border" v-if="!loadingDocuments && documents.length == 0">
            No documents found
        </div>
        <div v-if="documents.length > 0">
            <div class="form-inline mb-1">
                <label for="list-filter-input">Filter:</label>&nbsp;
                <input type="text" class="form-control form-control-sm" v-model="filter">
            </div>
            <b-table :fields="fields" :items="documents" :filter="filter" :filter-included-fields="filteredFields">
                <template v-slot:cell(action)="{item: document}">
                    <a href="#" @click.prevent="downloadFile(document)" title="Download document">
                        <i class="material-icons">cloud_download</i>
                    </a>
                    <a href="#" @click.prevent="showDetails(document)" title="Detailed information">
                        <i class="material-icons">info</i>
                    </a>
                    <a href="#" title="Delete document" class="text-danger" @click.prevent="deleteDocument(document)" v-if="user.id == document.uploader_id">
                        <i class="material-icons">delete</i>
                    </a>
                </template>    
            </b-table>
        </div>
        <b-modal v-model="showDetailedInfo" hide-footer v-if="currentDocument" :title="currentDocument.name" size="lg">
            <dl class="row">
                    <dt class="col-md-2">Name:</dt>
                    <dd class="col-md-10">{{currentDocument.name}}</dd>

                    <dt class="col-md-2">File name:</dt>
                    <dd class="col-md-10">{{currentDocument.file_name ? currentDocument.file_name : '--'}}</dd>

                    <dt class="col-md-2">Category:</dt>
                    <dd class="col-md-10">{{currentDocument.category ? currentDocument.category.name : '--'}}</dd>

                    <dt class="col-md-2">Date uploaded:</dt>
                    <dd class="col-md-10">{{currentDocument.created_at | formatDate('YYYY-MM-DD')}}</dd>

                    <dt class="col-md-2" v-if="currentDocument.uploader">Uploaded by:</dt>
                    <dd class="col-md-10">{{(currentDocument.uploader) ? getUploaderName(currentDocument.uploader) : '--'}}</dd>

                    <dt class="col-md-2">Notes:</dt>
                    <dd class="col-md-10">{{currentDocument.notes ? currentDocument.notes : '--'}}</dd>

            </dl>
            <div class="mt-2">
                <button 
                    class="btn btn-primary text-middle btn-sm"
                    @click="downloadFile(currentDocument)"
                    title="Download document"
                >
                    Download document
                </button>
            </div>
        </b-modal>
    </div>
</template>

<script>
    import DocumentUploader from './DocumentUploader'
    import getAllUploads from '../../../resources/uploads/get_all_uploads'
    import { mapGetters } from 'vuex';

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
                currentDocument: null,
                filter: '',
                fields: [
                    {
                        key: 'id',
                        sortable: true
                    },
                    {
                        key: 'name',
                        sortable: true
                    },
                    {
                        key: 'category.name',
                        sortable: true,
                        label: 'Category'
                    },
                    {
                        key: 'created_at',
                        label: 'Created',
                        sortable: true,
                        formatter: (value, key, item) => {
                            return this.$options.filters.formatDate(value, 'YYYY-MM-DD')
                        }
                    },
                    {
                        key: 'uploader',
                        label: 'Uploaded by',
                        sortable: true,
                        formatter: (value, key, item) => {
                            return this.getUploaderName(value);
                        }
                    },
                    'action'
                ],
                filteredFields: ['name', 'id', 'category', 'uploader', 'uploader']
            }
        },
        computed: {
            ...mapGetters({
                user: 'getUser'
            })
        },
        methods: {
            async getDocuments() {
                this.loadingDocuments = true;
                this.documents = await getAllUploads('with[]=category&with[]=uploader&where[user_id]='+this.volunteer.id)
                this.loadingDocuments = false;
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
            },
            deleteDocument(document) {
                if (confirm('Are you sure you want to delete the document '+document.name+'?')) {
                    axios.delete('/api/curator-uploads/'+document.id)
                        .then(response => {
                            this.getDocuments();
                        })
                }
            },
            getUploaderName(uploader) {
                if (uploader === null) {
                    return 'system';
                }
                if (this.user.isAdminOrProgrammer() || uploader.id  == this.user.id) {
                    return uploader.name;
                }
                return 'Coordinator';
            }
        },
        mounted() {
            this.getDocuments();
        }
    
}
</script>