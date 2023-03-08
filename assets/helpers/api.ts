import type { Slide } from '@/types/slide';

async function getApiToken(): Promise<string>
{
    const seed = window.location.hash.substring(1);
    const hashBuffer = await crypto.subtle.digest(
        'SHA-256',
        new TextEncoder().encode(seed)
    );
    const hashBytes = Array.from(new Uint8Array(hashBuffer));

    return hashBytes
        .map((byte) => byte.toString(16).padStart(2, '0'))
        .join('');
}

async function getSlides(): Promise<Slide[]>
{
    const apiToken = await getApiToken();

    return fetch('/api/slides', {
        headers: {
            'accept': 'application/json',
            'x-api-token': apiToken
        }
    }).then(
        (data) => data.json()
    );
}

function subscribeToEvents(handler: (event: MessageEvent) => void): void
{
    const eventSource = new EventSource(
        window.mercureHub,
        {
            withCredentials: true
        }
    );

    eventSource.onmessage = handler;
}

export { getSlides, subscribeToEvents };
