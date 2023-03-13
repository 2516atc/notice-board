import type { Slide } from '@/types/slide';

interface Event
{
    [ key: string ]: any;
    event: string;
}

interface RawAuthorisationRequest
{
    approved: boolean;
    code: string;
    expires: string | Date;
}

interface AuthorisationRequest extends RawAuthorisationRequest
{
    expires: Date;
}

async function getApiToken(): Promise<string|null>
{
    const seed = window.location.hash.substring(1);

    if (!seed)
        return null;

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

    if (!apiToken)
        throw new Error('No API token');

    return fetch('/api/slides', {
        headers: {
            'accept': 'application/json',
            'x-api-token': apiToken
        }
    }).then(
        (response) => {
            if (response.status === 401)
                throw new Error('Unauthorised');

            return response.json();
        }
    );
}

async function requestAuthorisation(): Promise<AuthorisationRequest>
{
    const apiToken = await getApiToken();

    if (!apiToken)
        throw new Error('No API token');

    const authorisationRequest: RawAuthorisationRequest = await fetch(
        '/api/authorisation-requests',
        {
            body: JSON.stringify({
                apiToken
            }),
            headers: {
                'accept': 'application/json',
                'Content-Type': 'application/json'
            },
            method: 'POST'
        }
    ).then(
        (response) => response.json()
    );

    return {
        ...authorisationRequest,
        expires: new Date(authorisationRequest.expires)
    };
}

function subscribeToEvents(handlers: { [pattern: string]: (event: MessageEvent) => void }): void
{
    const eventSource = new EventSource(
        window.mercureHub,
        {
            withCredentials: true
        }
    );

    eventSource.onopen = () => {
        eventSource.onerror = () => {
            window.refreshPage = true;
        }
    }

    eventSource.onmessage = async (event) => {
        const data: Event = JSON.parse(event.data);

        for (const pattern in handlers)
        {
            if (new RegExp(pattern).test(data.event))
                return handlers[pattern](event);
        }
    };
}

export { getSlides, requestAuthorisation, subscribeToEvents };
