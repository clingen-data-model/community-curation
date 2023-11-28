<template>
    <div>
        <div class="clearfix">
            <button class="btn btn-sm btn-primary float-right" @click="showCreateForm">Add note</button>
        </div>
        <div class="mt-2">
            <ul class="list-unstyled" v-if="notes.length > 0">
                <li v-for="note in notes" :key="note.id" class="mb-2">
                    <b-card>
                        <div class="float-right ml-3">
                            <button class="btn btn-default border btn-sm" @click="editNote(note)"><b-icon-pencil></b-icon-pencil></button>
                            <button class="btn btn-default border btn-sm" @click="deleteNote(note)"><b-icon-trash></b-icon-trash></button>
                        </div>
                        <p class="lead">{{note.content}}</p>
                        created on {{note.created_at | formatDate('YYYY-MM-DD hh:mm a')}} by {{note.creator.name}}
                    </b-card>
                </li>
            </ul>
            <div  class="alert alert-light border text-center" 
                v-else-if="loading">Loading notes...</div>
            <div v-else class="alert alert-light border text-center">
                There not any notes for this entry. <button class="btn btn-link" @click="showCreateForm">Add one</button>
            </div>
        </div>
        <b-modal title="Add a new note" hide-footer v-model="showingCreateForm">
            <note-form 
                :note="currentNote" 
                :notable-type="notableType" 
                :notable-id="notableId"
                @canceled="showingCreateForm = false"
                @saved="updateNotes"
            ></note-form>
        </b-modal>
    </div>
</template>
<script>
import { all as getAllNotes, destroy as destroyNote } from '../../resources/notes'
import NoteForm from './NoteForm'

export default {
    components: {
        NoteForm
    },
    props: {
        initialNotes: {
            type: Array,
            required: false,
            default: () => []
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
            notes: [],
            loading: false,
            showingCreateForm: false,
            currentNote: null
        }
    },
    watch: {
        initialNotes: {
            immediate: true,
            handler () {
                this.notes = this.initialNotes
            }
        },
        lookupParams() {
            this.getNotes();
        }
    },
    computed: {

    },
    methods: {
        async getNotes() {
            this.loading = true;
            this.notes = await getAllNotes({
                                    notable_type: this.notableType, 
                                    notable_id: this.notableId,
                                    with: ['creator']
                                })
                                .then(function (response) {
                                    return response.data.data;
                                });
            this.loading = false;
        },
        showCreateForm() {
            this.currentNote = {};
            this.showingCreateForm = true;
        },
        editNote(note) {
            this.currentNote = note;
            this.showingCreateForm = true;
        },
        updateNotes(note) {
            const idx = this.notes
                    .findIndex(n => n.id == note.id);
                    
            if (idx > -1) {
                this.notes[idx] = note;
                this.showingCreateForm = false;
                return;
            }

            this.notes.push(note);
            this.showingCreateForm = false;
        },
        deleteNote(note) {
            if (confirm('You are about to delete a note.  Are you sure you want to continue?')) {
                const idx = this.notes.findIndex( n => n.id == note.id);
                destroyNote(note)
                    .then(response => {
                        this.notes.splice(idx, 1);
                    })
            }
        }
    },
    mounted () {
        if (this.notes.length == 0) {
            this.getNotes();
        }
    }
}
</script> 