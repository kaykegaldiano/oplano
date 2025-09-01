<?php

declare(strict_types=1);

namespace App\Enums;

enum ClassUserRole: int
{
    case Monitor = 1;

    public function label(): string
    {
        return match ($this) {
            self::Monitor => 'Monitor',
        };
    }

    public static function options(): array
    {
        $opts = [];
        foreach (self::cases() as $case) {
            $opts[$case->value] = $case->label();
        }
        return $opts;
    }
}
