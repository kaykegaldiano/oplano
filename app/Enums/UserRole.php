<?php

declare(strict_types=1);

namespace App\Enums;

enum UserRole: int
{
    case AdminGlobal = 1;
    case CustomerSuccess = 2;
    case Monitor = 3;

    public function label(): string
    {
        return match ($this) {
            self::AdminGlobal => 'Administrador',
            self::CustomerSuccess => 'Customer Success',
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
