<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PerformanceType: string implements HasLabel
{
    case MEMORIZATION = 'memorization';
    case REVISION = 'revision';


    public function getLabel(): string
    {
        return match ($this) {
            self::MEMORIZATION => __('Memorization'),
            self::REVISION => __('Revision'),
        };
    }
}
