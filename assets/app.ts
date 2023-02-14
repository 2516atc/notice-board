import App from '@/App.vue';
import { store } from '@/store';
import { createApp } from 'vue';

const app = createApp(App);

app.mount('#app');

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
