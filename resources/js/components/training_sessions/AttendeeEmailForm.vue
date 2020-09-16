<template>
    <div class="border p-3 rounded">
        <div class="form-group row">
            <label class="col-lg-1">To:</label>
            <small class="col-lg-11">{{attendees.map(atnd => `${atnd.first_name} ${atnd.last_name}`).join(', ')}}</small>
        </div>
        <div class="form-group row">
            <label for="from" class="col-lg-1">From:</label>
            <div class="col-lg-6">
                <select v-model="fromEmail" class="form-control" id="from">
                    <option :value="$store.state.configs.mailFrom.address">{{$store.state.configs.mailFrom.address}}</option>
                    <option :value="$store.state.user.email">{{$store.state.user.email}}</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-1" for="subject">Subject:</label>
            <div class="col-lg-8">
                <input type="text" v-model="subject" class="form-control w-50" id="subject">
            </div>
        </div>
        <rich-text v-model="emailContent" id="message-body"></rich-text>

        <div class="mt-3">
            <button class="btn btn-sm btn-default border" @click="cancelEmail">Cancel</button>
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
            subject: '',
            emailContent: '',
            fromEmail: this.$store.state.configs.mailFrom.address
        }
    },
    methods: {
        ...mapMutations('messages', [
            'addInfo'
        ]),
        sendEmail () {
            if (this.emailContent == '') {
                alert('You must include a message to send to attendees.')
                return;
            }
            this.$emit('sending');
            window.axios.post('/api/training-sessions/'+this.trainingSession.id+'/attendees/email', {
                from: this.fromEmail,
                subject: this.subject,
                body: this.emailContent
            })
                .then(response => {
                    this.addInfo('Email sent');
                    this.initData();
                    this.$emit('sent');
                });
        },
        cancelEmail () {
            this.initData();
            this.$emit('canceled')
        },
        initData () {
            this.subject = '';
            this.emailContent = '';
        }
    }
}
</script>