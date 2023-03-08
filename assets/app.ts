import App from '@/App.vue';
import { getSlides, subscribeToEvents } from '@/helpers/api';
import { store } from '@/store';
import { createApp } from 'vue';

const app = createApp(App);

app.mount('#app');

async function startApp(): Promise<void>
{
    store.slides = await getSlides();
    store.currentSlide = store.slides[0];

    subscribeToEvents(async () => {
        store.slides = await getSlides();
    });

    setInterval(() => {
        const length = store.slides.length;

        if (length < 1)
            return;

        const currentIndex = store.slides.findIndex(
            (slide) => slide.id === store.currentSlide.id
        );
        const nextIndex = (currentIndex + 1) % length;

        store.currentSlide = store.slides[nextIndex];
    }, 5000);
}

startApp()
    .catch(console.error);
