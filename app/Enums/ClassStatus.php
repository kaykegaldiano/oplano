<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum ClassStatus: int implements HasLabel
{
    case Planned = 1;
    case Ongoing = 2;
    case Finished = 3;
    case Canceled = 4;

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Planned => 'Planejada',
            self::Ongoing => 'Em andamento',
            self::Finished => 'Finalizada',
            self::Canceled => 'Cancelada',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Planned => 'warning',
            self::Ongoing => 'success',
            self::Finished => 'gray',
            self::Canceled => 'danger',
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
