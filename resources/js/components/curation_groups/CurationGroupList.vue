<template>
    <div>
        <b-card title="Curation Groups">
            <div class="d-flex justify-content-between mb-2 p-0 filter-row">
                <div class="form-inline mr-2">
                    <label for="filter-input">Search:</label>
                    &nbsp;
                    <input type="text" 
                        class="form-control form-control-sm" 
                        v-model="filters.searchTerm" 
                        placeholder="filter rows" 
                        id="filter-input"
                    >
                </div>
                <b-pagination
                    size="sm"
                    hide-goto-end-buttons
                    :total-rows="totalRows"
                    :per-page="pageLength"
                    v-model="currentPage"
                    class="pl-3 mb-0"
                ></b-pagination>
            </div>
            <b-table 
                :fields="fields"
                :items="groupProvider"
                :sort-by.sync="sortKey"
                :sort-desc.sync="sortDesc"
                @sort-changed="handleSortChanged"
                :no-local-sorting="true"
                :show-empty="true"
                :filter="filters"
                :current-page="currentPage"
                @row-clicked="navigateToGroup"
            ></b-table>
            <div class="d-flex justify-content-between mb-2 p-0 filter-row">
                <div class="">
                </div>
                <b-pagination
                    size="sm"
                    hide-goto-end-buttons
                    :total-rows="totalRows"
                    :per-page="pageLength"
                    v-model="currentPage"
                    class="pl-3 mb-0"
                ></b-pagination>
            </div>

        </b-card>
    </div>
</template>
<script>
import getCurationGroups from '../../resources/curation_groups/get_all'

export default {
    props: {
        
    },
    data() {
        return {
            totalRows: 0,
            currentPage: 1,
            sortKey: 'curation_groups.name',
            sortDesc: false,
            filters: {
                searchTerm: null
            },
            pageLength: 25,
            fields: [
                {
                    key: 'id',
                    label: 'ID',
                    sortable: true
                },
                {
                    key: 'name',
                    label: 'Name',
                    sortable: true
                },
                {
                    key: 'curation_activity.name',
                    label: 'Curation Activity',
                    sortable: true
                },
                {
                    key: 'working_group.name',
                    label: 'Working Group',
                    sortable: true
                }
            ],
            loadingGroups: false
        }
    },
    computed: {

    },
    methods: {
        handleSortChanged() {
            resetCurrentPage();
        },
        handleFiltered() {
            this.resetCurrentPage();
        },
        resetCurrentPage () {
            this.currentPage = 1;
        },
        groupProvider (context, callback) {
            context.with = ['curationActivity', 'workingGroup'];

            this.loadingGroups = true;
            getCurationGroups(context)
                .then(response => {
                    this.totalRows = response.data.meta.total;
                    callback(response.data.data);
                    this.loadingGroups = false;
                });
        },
        navigateToGroup(group) {
            window.location = '/curation-groups/'+group.id
        },
    }
}
</script>