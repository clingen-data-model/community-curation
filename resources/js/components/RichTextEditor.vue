<template>
    <div>
        <ckeditor 
            :editor="editor" 
            :value="editorData" 
            @input="handleInput" 
            :config="mergedConfig"
            @ready="prefill"
        ></ckeditor>
        <div class="text-muted mt-2">To ensure links work correctly in emails, please use the <img class="border" src="/images/link-icon.png" /> icon in the menu bar</div>
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
                // toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo'],
                // skin: 'myskin'
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