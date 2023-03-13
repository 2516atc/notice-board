<template>
    <header>
        <span aria-hidden="true" class="crest"></span>
        <h1>{{ store.currentSlide.title ?? '2516 (Droitwich) Squadron' }}</h1>
    </header>
    <section>
        <component v-if="store.currentSlide" :is="slideComponent(store.currentSlide.type)" />
        <AuthorisationRequest v-if="store.authorisationRequestCode" />
    </section>
</template>

<script lang="ts" setup>
    import AuthorisationRequest from '@/components/authorisationRequest.vue';
    import { slideComponent } from '@/components/mapping';
    import { store } from './store';
</script>

<style lang="scss">
    @use '~@2516atc/ui/util/util';

    //noinspection CssUnknownTarget
    @import "https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap";

    html, body {
        font-family: arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    header {
        align-items: center;
        background-color: util.palette-colour(primary);
        display: flex;
        flex-direction: row;
        padding: 1.2rem 1.5rem;

        h1 {
            color: util.palette-colour(white);
            font-family: 'Montserrat', arial, sans-serif;
            font-size: 2.5rem;
            margin: 0;
        }

        .crest {
            background-image: url('./images/logo-2516@2x.png');
            background-position: center;
            background-repeat: no-repeat;
            background-size: contain;
            height: 90px;
            margin-right: 1rem;
            width: 67.5px;

            @include util.pixel-density-breakpoint(max 1) {
                background-image: url('./images/logo-2516@1x.png');
            }

            @include util.pixel-density-breakpoint(3) {
                background-image: url('./images/logo-2516@3x.png');
            }

            @include util.pixel-density-breakpoint(4) {
                background-image: url('./images/logo-2516@4x.png');
            }
        }
    }

    section {
        flex-grow: 1;
        font-size: 2rem;
        overflow-y: hidden;
        padding: 0 1.5rem;
    }
</style>
