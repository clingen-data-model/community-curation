<template>
    <question-block class="border-top pt-1 attestation-question">
        <template slot="question-text">
                {{question}}
        </template>
        <boolean-radio-group
            slot="answer-block"
            :name="name"
            v-model="proxyValue"
            class="ml-3 mt-1"
        ></boolean-radio-group>
    </question-block>

</template>
<script>
export default {
    props: {
        question: {
            type: String,
            required: true
        },
        name: {
            type: String,
            required: true
        },
        value: {
            type: [String, Number, Object]
        }
    },
    computed: {
        proxyValue: {
            immediate: true,
            get() {
                return this.value;
            },
            set(newValue) {
                this.$emit('input', newValue);
            }
        },
    }
}
</script>