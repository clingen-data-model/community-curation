<template>
    <div>
        <div class="mb-1">
            To: 
            <small>{{attendees.map(atnd => `${atnd.first_name} ${atnd.last_name}`).join(', ')}}</small>
        </div>
        <div class="form-inline mb-1">
            From:
            &nbsp;
            <select v-model="fromEmail" class="form-control form-control-sm">
                <option :value="$store.state.configs.mailFrom.address">{{$store.state.configs.mailFrom.address}}</option>
                <option :value="$store.state.user.email">{{$store.state.user.email}}</option>
            </select>
        </div>
        <rich-text-editor v-model="emailContent"></rich-text-editor>
        <div class="mt-1">
            <button class="btn btn-sm btn-default border" @click="$emit('canceled')">Cancel</button>
            <button class="btn btn-sm btn-primary" @click="sendEmail">Send Email</button>
        </div>
    </div>
</template>
<script>
import { mapMutations } from 'vuex'

export default {
    props: {
        trainingSession: {
            required: true,
            type: Object
        },
        attendees: {
            required: true,
            type: Array
        }
    },
    data() {
        return {
            emailContent: '',
            fromEmail: this.$store.state.configs.mailFrom.address
        }
    },
    methods: {
        ...mapMutations('messages', [
            'addInfo'
        ]),
        sendEmail () {
            this.$emit('sending');
            alert('sending message');
            // window.axios.post('/api/training-sessions/'+this.trainingSession.id+'/email')
            //     .then(response => {
                    this.addInfo('Email sent');
                    this.$emit('sent');
                // });
        }
    }
}
</script>