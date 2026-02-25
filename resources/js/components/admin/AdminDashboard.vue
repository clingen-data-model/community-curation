<template>
    <div>
        <div class="box">
            <div class="box-header with-border">
                <div class="box-title w-100 mb-2">Demographic Metrics</div>
                <div class="box-body">
                    <global-metrics ref="globalmetrics"></global-metrics>
                </div>
            </div>
        </div>
        <hr>
        <div class="box">
            <div class="box-header with-border">
                <div class="box-title w-100 mb-2">
                    <button class="btn btn-sm btn-light border float-right" @click="$refs.activitymetrics.getMetrics()">
                        &#8635;
                    </button>
                    Activity Metrics
                </div>
                <div class="box-body">
                    <activity-metrics ref="activitymetrics"></activity-metrics>
                </div>
            </div>
        </div>
        <hr>
        <row>
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border mb-2">
                        <div class="box-title">Logged in users</div>
                    </div>
                    <div class="box-body">
                        <logged-in-users-list></logged-in-users-list>
                    </div>
                </div>
            </div>
        </row>
    </div>
</template>

<script>
export default {
    name: 'AdminDashboard',
}
</script>
