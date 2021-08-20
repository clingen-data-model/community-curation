<template>
    <div>
        <ckeditor 
            :editor="editor" 
            :value="editorData" 
            @input="handleInput" 
            :config="mergedConfig"
            @ready="prefill"
        ></ckeditor>
    </div>
</template>
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
            },
        }
    },
    data() {
        return {
            editor: ClassicEditor,
            editorData: '',
            defaultConfig: {
                plugins: [ Link, AutoLink],
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo'],
                skin: 'myskin'
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
        },
        prefill () {
            this.editorData = this.value;
        }
    }
}
</script>