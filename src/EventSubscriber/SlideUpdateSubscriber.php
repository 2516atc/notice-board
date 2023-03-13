<?php

namespace App\EventSubscriber;

use App\Mercure\Mercure;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Events;

readonly class SlideUpdateSubscriber implements EventSubscriber
{
    public function __construct(private Mercure $mercure) { }

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
        $this->mercure->publish(
            $this->mercure->generateTopic('_api_slides_get_collection'),
            $event
        );
    }
}
