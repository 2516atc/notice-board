import type { Slide } from '@/types/slide';
import { reactive } from 'vue';

export const store = reactive({
    authorisationRequestCode: null as (string | null),
    currentSlide: {} as Slide,
    slides: [] as Slide[]
});
