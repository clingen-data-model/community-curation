<style></style>

<template>
        <div class="card p-3 mb-3">
            <h4>
                <span>Basic Information</span>
                <button class="btn btn-sm btn-default border float-right" @click="showBasicInfoForm = !showBasicInfoForm">Edit</button>
            </h4>
            
            <dl class="row">
                <dt class="col-sm-3">ORCHID ID:</dt>
                <dd class="col-sm-9">{{volunteer.orchid_id}}</dd>
                <dt class="col-sm-3">Email:</dt>
                <dd class="col-sm-9">
                    <span v-if="volunteer.email">{{ volunteer.email }}</span>
                </dd>

                <dt class="col-sm-3">Address:</dt>
                <dd class="col-sm-9">
                    <div v-if="volunteer.street1">{{ volunteer.street1 }}</div>
                    <div v-if="volunteer.street2">{{ volunteer.street2 }}</div>
                    <div>
                        <span v-if="volunteer.city">{{ volunteer.city }}</span>
                        <span v-if="volunteer.state">, {{ volunteer.state }}</span>
                        <span v-if="volunteer.zip">, {{ volunteer.zip }}</span>
                    </div>
                    <div v-if="volunteer.country_id">{{ countryLookup[volunteer.country_id] }}</div>
                </dd>
            </dl>

            <b-modal v-model="showBasicInfoForm" title="Edit Contact Info" hide-footer>
                <basic-info-form 
                    :volunteer="volunteer"
                    :countries="countries"
                    @saved="showBasicInfoForm = false"
                ></basic-info-form>
            </b-modal>
        </div>
</template>

<script>

    import getAllCountries from '../../../resources/volunteers/get_all_countries'
    import BasicInfoForm from './BasicInfoForm'

    export default {
        components: {
            BasicInfoForm
        },
        props: {
            volunteer: {
                reqired: true,
                type: Object
            }
        },
        data() {
            return {
                showBasicInfoForm: false,
                countries: []
            }
        },
        methods: {
            fetchCountries: async function() {
                this.countries = await getAllCountries();
            },
        },
        computed: {
            countryLookup() {
                const lookup = {}
                for (let country of this.countries) {
                    lookup[country.id] = country.name
                }
                return lookup
            }
        },
        mounted() {
            this.fetchCountries()
        }
    }
</script>