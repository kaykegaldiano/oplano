<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum EnrollmentStatus: int implements HasLabel
{
    case Active = 1;
    case Canceled = 2;
    case Completed = 3;
    case Pending = 4;

    public function getLabel(): string
    {
        return match ($this) {
            self::Active => 'Ativa',
            self::Canceled => 'Cancelada',
            self::Completed => 'Concluída',
            self::Pending => 'Pendente',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Canceled => 'danger',
            self::Completed => 'info',
            self::Pending => 'warning',
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
