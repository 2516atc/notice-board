<?php

namespace App\Document;

use ApiPlatform\Metadata\ApiProperty;
use App\ApiPlatform\OpenApiContext;
use App\ApiPlatform\SlideApiResource;
use App\Slide\SlideType;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
#[SlideApiResource(SlideType::GENERIC)]
class GenericSlide extends Slide
{
    #[MongoDB\Field]
    #[ApiProperty(openapiContext: OpenApiContext::MARKDOWN)]
    private string $text;

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
