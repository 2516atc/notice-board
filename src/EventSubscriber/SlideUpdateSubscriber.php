<?php

namespace App\EventSubscriber;

use ApiPlatform\Api\UrlGeneratorInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Events;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

readonly class SlideUpdateSubscriber implements EventSubscriber
{
    public function __construct(private HubInterface $hub, private UrlGeneratorInterface $router, private bool $private) { }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postRemove,
            Events::postUpdate
        ];
    }

    public function postPersist(LifecycleEventArgs $eventArgs): void
    {
        $this->fireSlideUpdatedEvent('slide_added');
    }

    public function postRemove(LifecycleEventArgs $eventArgs): void
    {
        $this->fireSlideUpdatedEvent('slide_removed');
    }

    public function postUpdate(LifecycleEventArgs $eventArgs): void
    {
        $this->fireSlideUpdatedEvent('slide_updated');
    }

    private function fireSlideUpdatedEvent(string $event): void
    {
        $topic = $this->router->generate('_api_slides_get_collection', referenceType: UrlGeneratorInterface::ABS_URL);

        $this->hub->publish(
            new Update(
                $topic,
                $event,
                $this->private
            )
        );
    }
}
