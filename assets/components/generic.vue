<template>
    <div v-html="markdownToHtml(currentSlide.text)"></div>
</template>

<script lang="ts">
    import { markdownToHtml } from '@/helpers/markdown';
    import { store } from '@/store';
    import type { Slide } from '@/types/slide';
    import { defineComponent } from 'vue';

    interface GenericSlide extends Slide
    {
        text: string
    }

    export default defineComponent({
        name: 'Generic',
        methods: {
            markdownToHtml
        },
        computed: {
            currentSlide() {
                return store.currentSlide as GenericSlide
            }
        }
    });
</script>
