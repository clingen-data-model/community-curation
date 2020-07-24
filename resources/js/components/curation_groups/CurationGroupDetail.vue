<template>
    <b-card>
        <template v-slot:header>
            <div class="d-flex justify-content-between">
                <h3 class="mb-0">
                    <a :href="`/${groupUrl}`">{{groupType}}</a> - {{group.name}}
                </h3>
                <div>
                    <a 
                        :href="`${adminBaseUrl}/${group.id}/edit`" 
                        class="btn btn-primary btn-sm" 
                        target="admin"
                        v-if="adminBaseUrl !== null"
                    >
                        Edit
                    </a>
                </div>
            </div>
        </template>
        <section class="lead">
            <dl class="row mb-0">
                <dt class="col-md-4">Curation activity</dt>
                <dd class="col-md-8">{{group.curation_activity.name}}</dd>
                
                <dt class="col-md-4">Working group</dt>
                <dd class="col-md-8">{{group.working_group ? group.working_group.name : '--'}}</dd>
                
                <dt class="col-md-4">Accepting Vollunteers</dt>
                <dd class="col-md-8">{{group.accepting_volunteers ? 'Yes' : 'No'}}</dd>

                <dt class="col-md-4">Url</dt>
                <dd class="col-md-8">{{group.url ? group.url : '--' }}</dd>
            </dl>
        </section>
        <section>
            <header>
                <h4>{{group.assignments.length}} Volunteers</h4>
            </header>

            <group-volunteer-info :assignments="group.assignments"></group-volunteer-info>

        </section>
        <section>
            <header>
                <h4>Notes</h4>
            </header>
            <notes-list notable-type="App\\CurationGroup" :notable-id="group.id"></notes-list>
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
                  curation_activity: {
                      id: 0,
                      name: 'loading...'
                  },
                  working_group: {
                      id: 0,
                      name: 'loading...'
                  },
                  accepting_volunteers: 1,
                  assignments: [],
              }
          }
      },
    },
    data() {
        return {
            group: this.initalGroup,
            groupType: 'Curation Groups',
            groupUrl: '/curation-groups',
            adminBaseUrl: '/admin/curation-group',
            assignmentStatusPath: 'status.name',
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

<style scoped>
    section {
        margin-bottom: 1rem;
        border-bottom: solid 1px #eee;
        padding-bottom: .5rem;
    }
</style>