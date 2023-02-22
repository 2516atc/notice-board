<?php

namespace App\Security;

use App\Document\ApiToken;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

readonly class UserProvider implements UserProviderInterface
{
    public function __construct(private DocumentManager $documentManager) { }

    public function loadUserByIdentifier($identifier): UserInterface
    {
        return $this
            ->documentManager
            ->getRepository(ApiToken::class)
            ->findOneBy([
                'id' => $identifier
            ]);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return ApiToken::class === $class || is_subclass_of($class, ApiToken::class);
    }
}
