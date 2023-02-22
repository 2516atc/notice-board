<?php

namespace App\ApiPlatform;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Slide\SlideType;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class SlideApiResource extends ApiResource
{
    public function __construct(SlideType $slideType)
    {
        parent::__construct(
            uriTemplate: '',
            operations: [
                new Post(),
                new Patch(uriTemplate: '/{id}'),
                new Put(uriTemplate: '/{id}')
            ],
            routePrefix: "slides/$slideType->value",
            security: "is_granted('ROLE_WRITE')",
            extraProperties: [
                'standard_put' => true,
            ]
        );
    }
}
