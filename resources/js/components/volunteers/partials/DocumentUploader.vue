<style></style>

<template>
    <div class="mb-2">
        <button class="btn btn-primary btn-sm" @click="showModal = true">Add Document</button>

        <b-modal 
            v-model="showModal"
            title="Upload a Document"
            @ok="uploadFile"
        >
            <div class="form-row">
                <label class="col-sm-2" for="file-name">File:</label>
                <div class="col-sm-10">
                    <input type="file" ref="uploadField" class="form-control-file form-control-sm">
                </div>
            </div>
            <div class="form-row">
                <label for="category_id" class="col-sm-2">Category:</label>
                <div class="col-sm-10">
                    <select name="category_id" id="category_id" class="form-control form-control-sm" v-model="newUpload.upload_category_id">
                        <option :value="null">None</option>
                        <option 
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{category.name}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <label for="notes" class="col-sm-2">
                    Notes:
                </label>
                <div class="col-sm-10">
                    <textarea name="notes" v-model="newUpload.notes" id="notes" cols="30" rows="5" class="form-control"></textarea>
                </div>
            </div>
        </b-modal>
    </div>
</template>

<script>
    export default {
        props: {
            volunteer: {
                required: true,
                type: Object
            }
        },  
        data() {
            return {
                showModal: false,
                categories: [],
                newUpload: this.initNewUpload()
            }
        },
        methods: {
            getUploadCategories() {
                window.axios.get('/api/upload-categories')
                    .then(response => this.categories = response.data.data)
            },
            initNewUpload() {
                return {
                    upload_category_id: null,
                    notes: ''
                }
            },
            uploadFile() {
                console.log('uploadFile');
                let formData = new FormData();
                formData.append('user_id', this.volunteer.id);
                formData.append('file', this.$refs.uploadField.files[0]);
                formData.append('upload_category_id', this.newUpload.upload_category_id);
                formData.append('notes', this.newUpload.notes);

                window.axios.post(
                    '/api/curator-uploads/', 
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                )
                .then(response => {
                    this.$emit('uploaded')
                    this.newUpload = this.initNewUpload();
                })
                .catch(error => {
                    console.log(error);
                    alert('There was a problem with your file upload');
                })

            },
            launchFileSelector() {
                this.$refs.uploadField.click();
            }
        },
        mounted() {
            this.getUploadCategories();
        }
    
}
</script>