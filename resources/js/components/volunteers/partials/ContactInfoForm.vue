<style></style>

<template>
    <div>
        <form>

            <div class="form-group row">
                <label for="name" class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="name" v-model="volunteer.name" placeholder="David Bowie">
                </div>
            </div>

            <hr>
            <div class="form-group row">
                <label for="email" class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="email" v-model="volunteer.email" placeholder="me@example.com">
                    <div class="text-muted">
                        <small>
                            This is the email {{$store.state.user.isVolunteer() ? 'you' : 'the volunteer'}} will use to log.
                        </small>
                    </div>
                </div>
            </div>

            <hr>
            <div class="form-group row">
                <label for="street1" class="col-sm-3 col-form-label">Address 1</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="street1" v-model="volunteer.street1" placeholder="1234 Main St">
                </div>
            </div>
            <div class="form-group row">
                <label for="street2" class="col-sm-3 col-form-label">Address 2</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="street2" v-model="volunteer.street2" placeholder="Apartment, suite, etc.">
                </div>
            </div>
            <div class="form-group row">
                <label for="city" class="col-sm-3 col-form-label">City</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="city" v-model="volunteer.city">
                </div>
            </div>
            <div class="form-group row">
                <label for="state" class="col-sm-3 col-form-label">State</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="state" v-model="volunteer.state">
                </div>
            </div>
            <div class="form-group row">
                <label for="zip" class="col-sm-3 col-form-label">Zip code</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="zip" v-model="volunteer.zip">
                </div>
            </div>
            <div class="form-group row">
                <label for="country_id" class="col-sm-3 col-form-label">Country</label>
                <div class="col-sm-9">
                    <select v-model="volunteer.country_id" class="form-control form-control-sm">
                        <option :value="null">Select&hellip;</option>
                        <option v-for="(country, idx) in countries" :key="idx" :value="country.id">
                            {{ country.name }}
                        </option>
                    </select>
                </div>
            </div>
            <button class="btn btn-primary" @click="updateContactInfo">Save</button>
        </form>
    </div>
</template>

<script>

    import updateVolunteer from '../../../resources/volunteers/update_volunteer'

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
                
            }
        },
        methods: {
            updateContactInfo(e) {
                e.preventDefault()
                updateVolunteer(
                    this.volunteer.id, 
                    {
                        'name': this.volunteer.name,
                        'email': this.volunteer.email,
                        'street1': this.volunteer.street1,
                        'street2': this.volunteer.street2,
                        'city': this.volunteer.city,
                        'state': this.volunteer.state,
                        'zip': this.volunteer.zip,
                        'country_id': this.volunteer.country_id
                    }
                ).then(response => {
                    this.$emit('saved');
                })
            }
        }
    }
</script>