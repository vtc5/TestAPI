<template>
    <BlockUI v-if="loading" :message="IndicatorMessage" :url="image"></BlockUI>
</template>

<script>
    import loadingImage from '../assets/ajax-loader.gif';
    export default {
        name: "loadingIndicator",
        props: {
            loading: false
        },
        data() {
            return {
                IndicatorMessage:"Loading...",
                image:loadingImage
            }
        },
}
</script>
