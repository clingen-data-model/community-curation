<template>
    <span>
        <a 
            class="nav-link btn-round" 
            href="#get-help" 
            title="Get help or report a problem" 
            v-b-modal.help-modal
        >
            ?
        </a>
        <b-modal id="help-modal" title="Get Answers" hide-footer>
            <p class="lead">Many common issues and questions are coverd in our check out the <a href="/faq" target="faq">FAQ</a>.</p>
            <p>
                If you still have any questions, please contact our help desk at 
                <a :href="`mailto:${$store.state.configs.mailFrom.address}`">{{$store.state.configs.mailFrom.address|ucfirst}}</a>.
            </p>
        </b-modal>

    </span>
</template>
<script>
export default {
}
</script>   