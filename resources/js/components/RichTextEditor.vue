<template>
    <div>
        <ckeditor :editor="editor" :value="value" @input="$emit('input', value)" :config="editorConfig"></ckeditor>
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
            }
        }
    },
    data() {
        return {
            editor: ClassicEditor
        }
    },
}
</script>