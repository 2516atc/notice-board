<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Security\Core\User\UserInterface;

#[MongoDB\Document(collection: 'api_tokens')]
class ApiToken implements UserInterface
{
    #[MongoDB\Id]
    private string $id;

    #[MongoDB\Field]
    #[MongoDB\UniqueIndex]
    private string $token;

    #[MongoDB\Field(type: 'collection')]
    private ?array $roles = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        $roles = $this->roles ?? [];
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = empty($roles) ?
            null :
            $roles;

        return $this;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
