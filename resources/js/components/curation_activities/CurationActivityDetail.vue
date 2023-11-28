<template>
    <b-card>
        <template v-slot:header>
            <div class="d-flex justify-content-between">
                <h3 class="mb-0">
                    <a :href="`${groupUrl}`">{{groupType}}</a> - {{group.name}}
                </h3>
            </div>
        </template>
        <section>
            <header>
                <h4>{{group.assignments.length}} Volunteers</h4>
            </header>

            <group-volunteer-info :assignments="group.assignments"></group-volunteer-info>

        </section>
    </b-card>
</template>

<script>
import GroupVolunteerInfo from '../GroupVolunteerInfo'

export default {
    components: {
        GroupVolunteerInfo
    },
    props: {
      initialGroup: {
          type: Object,
          default() {
              return {
                  name: 'loading...',
                  assignments: [],
              }
          }
      },
    },
    data() {
        return {
            group: this.initalGroup,
            groupType: 'Curation Activities',
            groupUrl: '/curation-activities',
            additionalTableFields: [
                {
                    key: 'sub_assignments',
                    label: 'Curation groups',
                    formatter (value) {
                        // console.log(value);
                        return value.map(subAsn => subAsn.assignable.name).join(", ");
                    },
                    sortable: true
                }
            ]
        }
    },
    watch: {
        initialGroup: {
            immediate: true,
            handler() {
                this.group = this.initialGroup
            }
        }
    },
    methods: {

    }
}
</script>