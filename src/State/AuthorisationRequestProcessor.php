<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\AuthorisationRequest;
use App\Document\ApiToken;
use DateInterval;
use DateTime;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

readonly class AuthorisationRequestProcessor implements ProcessorInterface
{
    public function __construct(private CacheInterface $cache, private DocumentManager $documentManager) { }

    /**
     * @inheritDoc
     * @param AuthorisationRequest $data
     * @throws InvalidArgumentException
     * @throws MongoDBException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($operation instanceof Post)
        {
            $expires = (new DateTime())->add(
                new DateInterval('PT5M')
            );
            $requestCode = $this->saveAuthorisationRequest($data->apiToken, $expires);

            return new AuthorisationRequest(
                code: $requestCode,
                apiToken: $data->apiToken,
                expires: $expires
            );
        }

        if ($operation instanceof Patch)
        {
            if ($data->approved)
            {
                $apiToken = (new ApiToken())->setToken($data->apiToken);

                $this->documentManager->persist($apiToken);
                $this->documentManager->flush();
            }

            $this->cache->delete(AuthorisationRequest::CACHE_PREFIX . $data->code);
        }

        return $data;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function saveAuthorisationRequest(string $apiToken, DateTime $expires): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $codeLength = 6;

        $code = implode(
            array_map(
                fn() => $characters[mt_rand(0, strlen($characters) - 1)],
                range(1, $codeLength)
            )
        );

        $cachedToken = $this->cache->get(
            AuthorisationRequest::CACHE_PREFIX . $code,
            function(ItemInterface $item) use ($apiToken, $expires) {
                $item->expiresAt($expires);

                return $apiToken;
            }
        );

        if ($apiToken === $cachedToken)
            return $code;

        return $this->saveAuthorisationRequest($apiToken, $expires);
    }
}
