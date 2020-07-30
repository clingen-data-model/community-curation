<template>
    <div class="w-75 m-auto">
    <b-card>
        <h2 slot="header">{{title}}</h2>
        <slot></slot>

        <div class="d-flex" style="font-size: 1rem" v-show="signable">
            <div>
                <input 
                    type="checkbox" 
                    :value="1" 
                    id="sig-checkbox" 
                    class="mr-3 pt-1" 
                    style="transform: scale(1.5);"
                    :disabled="!signable"
                    name="signature"
                    v-model="signature"
                >
            </div>
            <label for="sig-checkbox" >
                <slot name="signature-text">
                    
                </slot>
            </label>
        </div>

        <div slot="footer">
            <button class="btn btn-default" @click="cancelFormSubmission">Cancel</button>
            <button class="btn btn-primary" :disabled="!submitable" type="submit">Complete Attestation</button>
        </div>        
    </b-card>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        props: {
            title: {
                type: String,
                required: true
            },
            signable: {
                type: Boolean,
                required: true
            }
        },
        data() {
            return {
                submitting: false,
                signature: null
            }
        },
        computed: {
            submitable: function () {
                return this.signable && this.signature;
            }
        },
        watch: {
            signable() {
                if (!this.signable) {
                    this.signature = false;
                }
            }
        },
        methods: {
            cancelFormSubmission() {
                window.history.back();
            },
        }
    
}
</script>