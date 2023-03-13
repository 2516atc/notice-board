<template>
    <p>Use this code to authorise this device:</p>
    <p class="code">
        <span v-for="character in code">{{ character }}</span>
    </p>
</template>

<script lang="ts">
    import { store } from '@/store';
    import { defineComponent } from 'vue';

    export default defineComponent({
        name: 'AuthorisationRequest',
        computed: {
            code() {
                return store.authorisationRequestCode
            }
        }
    });
</script>

<style lang="scss" scoped>
    p.code {
        font-size: 2em;

        span:not(:last-of-type) {
            margin-right: 1.5rem;
        }

        span:nth-child(3)::after {
            content: '-';
            margin-left: 1.5rem;
        }
    }
</style>
