<template>
    <div>
        <b-modal
            v-model="showing"
            title="ACHTUNG!"
            @ok="continueOnDemo"
            @cancel="goToProd"
            cancel-title-html="Go to <strong>ccdb.clinicalgenome.org</strong>"
            ok-title-html="Continue with <strong>testing or training</strong>"
            size="lg"
            variant="warning"
            hide-header="true"
            cancel-variant="primary"
            ok-variant="warning"
        >
            <div class="alert alert-warning">
                <h3>☠️ This is not the real CCDB! ☠️</h3>
                <p>This is a <strong>DEMO</strong> system for testing and training.</p>

                <p>
                    If you are surprised you're seeing this you probably followed a bad bookmark.
                </p>
                
                <p>To go to the production CCDB click the 'Go to ccdb.clinicalgenome.org' button below.</p>
                
                <p>Please make sure you update your bookmarks!</p>
                
            </div>

        </b-modal>
    </div>
</template>
<script>
export default {
    props: {
        
    },
    data() {
        return {
            showing: sessionStorage.getItem('seen-demo-warning') !== null ? false : true
        }
    },
    methods: {
        goToProd () {
            window.location = 'https://ccdb.clinicalgenome.org'
        },
        continueOnDemo () {
            showing: false
            sessionStorage.setItem('seen-demo-warning', 1)
        }
    }
}
</script>