<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ClassModality: int implements HasLabel
{
    case Online = 1;
    case Presential = 2;
    case Hybrid = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::Online => 'Online',
            self::Presential => 'Presencial',
            self::Hybrid => 'Híbrida',
        };
    }

    public static function options(): array
    {
        $opts = [];
        foreach (self::cases() as $case) {
            $opts[$case->value] = $case->getLabel();
        }
        return $opts;
    }
}
