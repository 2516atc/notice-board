<template>
    <div v-html="markdownToHtml(store.currentSlide.data['text'])"></div>
</template>

<script lang="ts">
    import { markdownToHtml } from '@/helpers/markdown';
    import { store } from '@/store';
    import { defineComponent } from 'vue';

    export default defineComponent({
        name: 'Generic',
        methods: {
            markdownToHtml
        },
        data() {
            return {
                store
            };
        }
    });
</script>

<style scoped>

</style>
