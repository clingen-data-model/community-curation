<template>
    <div>
        <ckeditor :editor="editor" :value="value" @input="handleInput" :config="mergedConfig"></ckeditor>
    </div>
</template>
<style>
    .ck-editor__editable_inline {
        min-height: 200px;
    }
</style>
<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import CKEditor from '@ckeditor/ckeditor5-vue';

export default {
    components: {
        ckeditor: CKEditor.component
    },
    props: {
        value: {
            type: String,
            default: ''
        },
        editorConfig: {
            type: Object,
            default: function () {
                return {}
            }
        }
    },
    data() {
        return {
            editor: ClassicEditor,
            defaultConfig: {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'table', 'undo', 'redo']
            }
        }
    },
    computed: {
        mergedConfig() {
            return {...this.defaultConfig, ...this.editorConfig}
        }
    },
    methods: {
        handleInput (val) {
            this.$emit('input', val)
        }
    }
}
</script>