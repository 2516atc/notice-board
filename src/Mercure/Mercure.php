<?php

namespace App\Mercure;

use ApiPlatform\Api\UrlGeneratorInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

readonly class Mercure
{
    public function __construct(
        private HubInterface $hub,
        private UrlGeneratorInterface $router,
        public bool $privateEvents
    ) { }

    public function generateTopic(string $route, array $parameters = []): string
    {
        return $this->router->generate($route, $parameters, UrlGeneratorInterface::ABS_URL);
    }

    public function publish(string $topic, string $event, array $data = []): string
    {
        $data = array_merge([ 'event' => $event ], $data);

        return $this->hub->publish(
            new Update(
                $topic,
                json_encode($data),
                $this->privateEvents
            )
        );
    }
}
