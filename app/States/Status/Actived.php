<?php

namespace App\States\Status;

class Actived extends StatusState
{
    public static string $name = 'Actived';

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
