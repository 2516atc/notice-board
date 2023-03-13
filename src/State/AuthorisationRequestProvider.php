<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\AuthorisationRequest;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

readonly class AuthorisationRequestProvider implements ProviderInterface
{
    public function __construct(private CacheInterface $cache) { }

    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $code = $uriVariables['code'];

        $cachedToken = $this->cache->get(AuthorisationRequest::CACHE_PREFIX . $code, function(ItemInterface $item) {
            $item->expiresAfter(0);

            return null;
        });

        if (!$cachedToken)
            return null;

        return new AuthorisationRequest(
            code: $code,
            apiToken: $cachedToken
        );
    }
}
