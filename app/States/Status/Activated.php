<?php

namespace App\States\Status;

class Activated extends StatusState
{
    public static string $name = 'Aktif';

    public function label(): string
    {
        return 'Aktif';
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
