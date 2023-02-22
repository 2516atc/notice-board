<?php

namespace App\Slide;

use App\Document\GenericSlide;

enum SlideType: string
{
    case GENERIC = 'generic';

    public const MAPPING = [
        self::GENERIC->value => GenericSlide::class
    ];
}
