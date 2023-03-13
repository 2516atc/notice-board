<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\State\AuthorisationRequestProcessor;
use App\State\AuthorisationRequestProvider;
use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Patch(
            normalizationContext: ['groups' => ['response']],
            denormalizationContext: ['groups' => ['patch-request']],
            security: "is_granted('ROLE_GRANT_AUTH')"
        ),
        new Post(
            normalizationContext: ['groups' => ['post-response', 'response']],
            denormalizationContext: ['groups' => ['post-request']]
        )
    ],
    provider: AuthorisationRequestProvider::class,
    processor: AuthorisationRequestProcessor::class
)]
class AuthorisationRequest
{
    const CACHE_PREFIX = 'auth/request/';

    public function __construct(
        #[ApiProperty(identifier: true)]
        #[Assert\Length(6)]
        #[Groups('response')]
        public readonly ?string $code = null,

        #[Assert\Length(64)]
        #[Groups('post-request')]
        public readonly ?string $apiToken = null,

        #[Groups(['patch-request', 'response'])]
        public bool $approved = false,

        #[Groups(['post-response'])]
        public readonly ?DateTime $expires = null
    ) { }
}
