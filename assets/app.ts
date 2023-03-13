import App from '@/App.vue';
import { getSlides, requestAuthorisation, subscribeToEvents } from '@/helpers/api';
import { store } from '@/store';
import { createApp } from 'vue';

const app = createApp(App);

app.mount('#app');

async function startApp(): Promise<void>
{
    try
    {
        store.slides = await getSlides();
        store.currentSlide = store.slides[0];
    }
    catch (exception)
    {
        if (!(exception instanceof Error) || exception.message !== 'Unauthorised')
            throw exception;

        const authorisationRequest = await requestAuthorisation();

        if (authorisationRequest.approved)
        {
            store.slides = await getSlides();
            store.currentSlide = store.slides[0];
        }
        else
        {
            store.authorisationRequestCode = authorisationRequest.code;

            setTimeout(
                () => location.reload(),
                authorisationRequest.expires.getTime() - Date.now()
            )
        }
    }

    subscribeToEvents({
        'slide_.+': async () => {
            store.slides = await getSlides();
        },
        'auth_.+': () => {
            location.reload();
        }
    });

    setInterval(() => {
        if (window.refreshPage)
        {
            location.reload();
            return;
        }

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
