import type { Slide } from '@/types/slide';
import { reactive } from 'vue';

export const store = reactive({
    currentSlide: {} as Slide,
    slides: [] as Slide[]
});
