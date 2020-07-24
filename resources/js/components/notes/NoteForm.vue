<template>
    <div>
        <textarea cols="30" rows="10" class="form-control mb-2" v-model="noteCopy.content"></textarea>
        <button class="btn btn-light border" @click="cancel">Cancel</button>
        <button class="btn btn-primary" @click="saveNote">Save</button>
    </div>
</template>
<script>
import {create as createNote, update as updateNote } from './../../resources/notes'

export default {
    props: {
        note: {
            type: Object,
            required: false,
            default: () => {}
        },
        notableType: {
            type: String,
            required: true
        },
        notableId: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            noteCopy: {},
            notabletypes: [
                {
                    'id': 'App\\User',
                    'label': 'Volunteer'
                },
                {
                    'id': 'App\\CurationGroup',
                    'label': 'Curation group'
                }
            ]
        }
    },
    computed: {

    },
    watch: {
        note: {
            immediate: true,
            handler() {
                this.noteCopy = JSON.parse(JSON.stringify(this.note));
            }
        },
        notableType: {
            immediate: true,
            handler() {
                this.$set(this.noteCopy, 'notable_type', this.notableType) 
            }
        },
        notableId: {
            immediate: true,
            handler() {
                this.$set(this.noteCopy, 'notable_id', this.notableId) 
            }
        }
    },
    methods: {
        cancel() {
            this.noteCopy = {};
            this.$emit('canceled');
        },
        saveNote() {
            if (this.noteCopy.id) {
                updateNote(this.noteCopy.id, this.noteCopy)
                    .then(response => {
                        this.noteCopy = {
                            notable_type: this.notableType,
                            notable_id: this.notableId
                        };
                        this.$emit('saved', response.data.data);
                    });
            } else {
                createNote(this.noteCopy)
                    .then(response => {
                        this.noteCopy = {
                            notable_type: this.notableType,
                            notable_id: this.notableId
                        };
                        this.$emit('saved', response.data.data);
                    });
            }
        }
    }
}
</script>