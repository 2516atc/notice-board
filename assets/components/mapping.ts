import Generic from '@/components/generic.vue';
import type { defineComponent } from 'vue';

function slideComponent(slideType: string): ReturnType<typeof defineComponent>
{
    return {
        'generic': Generic
    }[slideType];
}

export { slideComponent };
