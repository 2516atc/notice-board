<?php

namespace App\Document;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\ApiPlatform\SlideApiResource;
use App\Slide\SlideType;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use ReflectionClass;

#[
    MongoDB\Document(collection: 'slides'),
    MongoDB\DiscriminatorField('type'),
    MongoDB\DiscriminatorMap(SlideType::MAPPING),
    MongoDB\InheritanceType('SINGLE_COLLECTION')
]
#[ApiResource(
    uriTemplate: 'slides/{id}',
    operations: [
        new GetCollection(uriTemplate: 'slides'),
        new Get(),
        new Delete(security: "is_granted('ROLE_WRITE')")
    ],
    security: "is_granted('ROLE_USER')"
)]
abstract class Slide
{
    #[MongoDB\Id]
    private string $id;

    #[MongoDB\Field]
    private string $title;

    public function getType(): ?string
    {
        $attributes = (new ReflectionClass($this))
            ->getAttributes(SlideApiResource::class);

        if (empty($attributes))
            return null;

        $arguments = $attributes[0]->getArguments();

        return (empty($arguments) || !($arguments[0] instanceof SlideType)) ?
            null :
            $arguments[0]->value;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
