<style>
    ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
        color: #c0c0c0 !important;
    }
    ::-ms-input-placeholder { /* Microsoft Edge */
        color: #c0c0c0 !important;
    }
</style>

<template>
    <div>
        <form>

            <div class="form-group row">
                <label for="name" class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-6">
                    <div class="form-inline">
                        <input type="text" class="form-control" id="first_name" v-model="volunteer.first_name" placeholder="First">
                        &nbsp;
                        <input type="text" class="form-control" id="last_name" v-model="volunteer.last_name" placeholder="Last">
                    </div>
                    <validation-error :errors="errors.first_name"></validation-error>
                    <validation-error :errors="errors.last_name"></validation-error>
                </div>
            </div>

            <hr>
            <div class="form-group row">
                <label for="email" class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="email" v-model="volunteer.email" placeholder="me@example.com">
                    <div class="text-muted">
                        <small>
                            This is the email {{$store.state.user.isVolunteer() ? 'you' : 'the volunteer'}} will use to log in.
                        </small>
                    </div>
                    <validation-error :errors="errors.email"></validation-error>
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label for="name" class="col-sm-3 col-form-label">ORCiD ID</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="orcidid" v-model="volunteer.orcid_id" placeholder="123123123">
                </div>
            </div>

            <div class="form-group row">
                <label for="name" class="col-sm-3 col-form-label">hypothes.is username</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="hypothesis-id-field" v-model="volunteer.hypothesis_id" placeholder="my-hypothesis-username">
                </div>
            </div>

            <hr>
            <div class="form-group row">
                <label for="email" class="col-sm-3 col-form-label">Institution</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="institution" v-model="volunteer.institution" placeholder="UNC Chapel Hill">
                </div>
            </div>

            <div class="form-group row">
                <label for="street1" class="col-sm-3 col-form-label">Address 1</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="street1" v-model="volunteer.street1" placeholder="1234 Main St">
                </div>
            </div>
            <div class="form-group row">
                <label for="street2" class="col-sm-3 col-form-label">Address 2</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="street2" v-model="volunteer.street2" placeholder="Apartment, suite, etc.">
                </div>
            </div>
            <div class="form-group row">
                <label for="city" class="col-sm-3 col-form-label">City</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="city" v-model="volunteer.city">
                </div>
            </div>
            <div class="form-group row">
                <label for="state" class="col-sm-3 col-form-label">State</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="state" v-model="volunteer.state">
                </div>
            </div>
            <div class="form-group row">
                <label for="zip" class="col-sm-3 col-form-label">Zip code</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="zip" v-model="volunteer.zip">
                </div>
            </div>
            <div class="form-group row">
                <label for="country_id" class="col-sm-3 col-form-label">Country</label>
                <div class="col-sm-6">
                    <select v-model="volunteer.country_id" class="form-control form-control-sm">
                        <option :value="null">Select&hellip;</option>
                        <option v-for="(country, idx) in countries" :key="idx" :value="country.id">
                            {{ country.name }}
                        </option>
                    </select>
                    <validation-error :errors="errors.country_id"></validation-error>
                </div>
            </div>

            <div class="form-group row">
                <label for="timezone" class="col-sm-3 col-form-label">Closest City <small>(for timezone)</small></label>
                <div class="col-sm-6">
                    <select name="" id="" v-model="timezone" class="form-control form-control-sm">
                        <option value="UTC">Select&hellip;</option>
                        <option :value="tz" v-for="tz in timezones" :key="tz">{{tz}}</option>
                    </select>
                    <validation-error :errors="errors.timezone"></validation-error>
                </div>
            </div>
            <button class="btn btn-primary" @click="updateContactInfo">Save</button>
        </form>
    </div>
</template>

<script>

    import updateVolunteer from '../../../resources/volunteers/update_volunteer'
    import getAllTimezones from '../../../resources/timezones/all'


    export default {
        props: {
            volunteer: {
                type: Object,
                required: true
            },
            countries: {
                type: Array,
                required: true
            }
        },
        data: function() {
            return {
                errors: {},
                timezones: [],
            }
        },
        computed: {
            timezone: {
                get: function () {
                    if (!this.volunteer.timezone || this.volunteer.timezone === null) { 
                        this.volunteer.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                     } 
                    return this.volunteer.timezone
                },
                set: function (value) {
                    this.volunteer.timezone = value;
                }
            }
        },
        methods: {
            updateContactInfo(e) {
                e.preventDefault()
                updateVolunteer(
                    this.volunteer.id, 
                    {
                        'first_name': this.volunteer.first_name,
                        'last_name': this.volunteer.last_name,
                        'orcid_id': this.volunteer.orcid_id,
                        'hypothesis_id': this.volunteer.hypothesis_id,
                        'email': this.volunteer.email,
                        'institution': this.volunteer.institution,
                        'street1': this.volunteer.street1,
                        'street2': this.volunteer.street2,
                        'city': this.volunteer.city,
                        'state': this.volunteer.state,
                        'zip': this.volunteer.zip,
                        'country_id': this.volunteer.country_id,
                        'timezone': this.volunteer.timezone
                    }
                ).then(response => {
                    this.$emit('saved');
                })
                .catch(error => {
                    console.log(error.response.data.errors);
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors
                    }
                })
            }
        },
        async mounted() {
            this.timezones = await getAllTimezones();
        }
    }
</script>