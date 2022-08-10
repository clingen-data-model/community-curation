<template>
    <div>
        <form>
            <div class="form-group row">
                <label for="name" class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-6">
                    <div class="form-inline">
                        <input type="text" class="form-control" id="first_name" v-model="updatedVolunteer.first_name" placeholder="First">
                        &nbsp;
                        <input type="text" class="form-control" id="last_name" v-model="updatedVolunteer.last_name" placeholder="Last">
                    </div>
                    <validation-error :errors="errors.first_name"></validation-error>
                    <validation-error :errors="errors.last_name"></validation-error>
                </div>
            </div>

            <hr>
            <div class="form-group row">
                <label for="email" class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="email" v-model="updatedVolunteer.email" placeholder="me@example.com">
                    <div class="text-muted">
                        <small>
                            This is the email 
                            {{
                                isPerson 
                                ? 'you' 
                                : 'the volunteer'
                            }} will use to log in.
                        </small>
                    </div>
                    <validation-error :errors="errors.email"></validation-error>
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <label for="name" class="col-sm-3 col-form-label">ORCiD ID</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="orcidid" v-model="updatedVolunteer.orcid_id" placeholder="XXXX-XXXX-XXXX-XXXX">
                </div>
            </div>

            <div class="form-group row">
                <label for="name" class="col-sm-3 col-form-label">hypothes.is username</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="hypothesis-id-field" v-model="updatedVolunteer.hypothesis_id" placeholder="my-hypothesis-username">
                </div>
            </div>

            <hr>
            <div class="form-group row">
                <label for="email" class="col-sm-3 col-form-label">Institution</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="institution" v-model="updatedVolunteer.institution" placeholder="UNC Chapel Hill">
                </div>
            </div>

            <div class="form-group row">
                <label for="street1" class="col-sm-3 col-form-label">Address 1</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="street1" v-model="updatedVolunteer.street1" placeholder="1234 Main St">
                </div>
            </div>
            <div class="form-group row">
                <label for="street2" class="col-sm-3 col-form-label">Address 2</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="street2" v-model="updatedVolunteer.street2" placeholder="Apartment, suite, etc.">
                </div>
            </div>
            <div class="form-group row">
                <label for="city" class="col-sm-3 col-form-label">City</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="city" v-model="updatedVolunteer.city">
                </div>
            </div>
            <div class="form-group row">
                <label for="state" class="col-sm-3 col-form-label">State</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="state" v-model="updatedVolunteer.state">
                </div>
            </div>
            <div class="form-group row">
                <label for="zip" class="col-sm-3 col-form-label">Zip code</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="zip" v-model="updatedVolunteer.zip">
                </div>
            </div>
            <div class="form-group row">
                <label for="country_id" class="col-sm-3 col-form-label">Country</label>
                <div class="col-sm-6">
                    <select v-model="updatedVolunteer.country_id" class="form-control form-control-sm">
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

            <user-can permission="set clingen member info">
                <hr>
                <div class="form-group row">
                    <label for="member-info" class="col-sm-3 col-form-label">Already a ClinGen member</label>
                    <div class="col-sm-6">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="clingen-member-info-no" :value="0" v-model="updatedVolunteer.already_clingen_member" @change="clearAlreadyMemberEps">
                            <label class="form-check-label" for="clingen-member-info-no">No</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="clingen-member-info-yes" :value="1" v-model="updatedVolunteer.already_clingen_member">
                            <label class="form-check-label" for="clingen-member-info-yes">Yes</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row" v-if="updatedVolunteer.isClingenMember()">
                    <label class="col-sm-3 col-form-label">Curation groups:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" v-model="cgFilter" placeholder="filter">
                        <div class="border p-1 mt-2" style="height: 200px; overflow-y: scroll">
                            <div class="form-check form-check" v-for="group in filteredCurationGroups" :key="group.id">
                                <input 
                                    :value="group.id" 
                                    v-model="updatedVolunteer.already_member_cgs"
                                    type="checkbox" 
                                    :id="`curation-group-checkbox-${group.id}`" 
                                    class="form-check-input"
                                >
                                <label class="form-check-label" :for="`curation-group-checkbox-${group.id}`">
                                    {{group.name}}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </user-can>

            <button class="btn btn-primary" @click="updateContactInfo">Save</button>
        </form>
    </div>
</template>

<script>

    import updateVolunteer from '../../../resources/volunteers/update_volunteer'
    import getAllTimezones from '../../../resources/timezones/all'
    import getAllCurationGroups from '../../../resources/curation_groups/get_all_curation_groups'


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
                curationGroups: [],
                cgFilter: null,
                updatedVolunteer: this.volunteer.clone()
            }
        },
        computed: {
            isPerson () {
                return this.$store.state.user.isVolunteer() || this.$store.state.user.isCurrentUser(this.volunteer)
            },
            timezone: {
                get: function () {
                    if (!this.updatedVolunteer.timezone || this.updatedVolunteer.timezone === null) { 
                        this.updatedVolunteer.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                     } 
                    return this.updatedVolunteer.timezone
                },
                set: function (value) {
                    this.updatedVolunteer.timezone = value;
                }
            },
            filteredCurationGroups() {
                if (this.cgFilter) {
                    return this.curationGroups.filter(item => item.name.toLowerCase().includes(this.cgFilter.toLowerCase()))
                }

                return this.curationGroups
            }
        },
        methods: {
            clearAlreadyMemberEps(evt) {
                if (this.updatedVolunteer.already_clingen_member == 0) {
                    this.updatedVolunteer.already_member_cgs = []
                }
            },
            updateContactInfo(e) {
                e.preventDefault()
                updateVolunteer(
                    this.updatedVolunteer.id, 
                    {
                        'first_name': this.updatedVolunteer.first_name,
                        'last_name': this.updatedVolunteer.last_name,
                        'orcid_id': this.updatedVolunteer.orcid_id,
                        'hypothesis_id': this.updatedVolunteer.hypothesis_id,
                        'email': this.updatedVolunteer.email,
                        'institution': this.updatedVolunteer.institution,
                        'street1': this.updatedVolunteer.street1,
                        'street2': this.updatedVolunteer.street2,
                        'city': this.updatedVolunteer.city,
                        'state': this.updatedVolunteer.state,
                        'zip': this.updatedVolunteer.zip,
                        'country_id': this.updatedVolunteer.country_id,
                        'timezone': this.updatedVolunteer.timezone,
                        'already_clingen_member': this.updatedVolunteer.already_clingen_member,
                        'already_member_cgs': this.updatedVolunteer.already_member_cgs
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
            },
            cancelUpdate() {
                this.updatedVolunteer = this.volunteer.clone()
            }
        },
        async mounted() {
            this.timezones = await getAllTimezones();
            this.curationGroups = await getAllCurationGroups();
        }
    }
</script>