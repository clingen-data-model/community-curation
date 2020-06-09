<style></style>

<template>
    <div class="validation-error alert alert-danger p-1 mt-1" v-if="hasErrors">
        {{joinedErrors}}
    </div>
</template>

<script>
    export default {    
        props: {
            errors: {
                required: true
            }
        },
        computed: {
            hasErrors: function () {
                return this.errors && this.errors.length > 0
            },
            joinedErrors: function () {
                if (this.errors) {
                    return this.errors.join(', ')
                }
            }
        }
    }
</script>