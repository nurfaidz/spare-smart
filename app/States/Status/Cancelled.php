<?php

namespace App\States\Status;

class Cancelled extends StatusState
{
    public static string $name = 'Cancelled';

    public function label(): string
    {
        return 'Dibatalkan';
    }

    public function color(): string
    {
        return 'danger';
    }

    public function toLivewire(): static
    {
        return new static(static::getModel());
    }

    public static function fromLivewire($value)
    {
        return $value;
    }
}
