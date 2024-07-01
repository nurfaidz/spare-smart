<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;

class Report extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reportable()
    {
        return $this->morphTo();
    }

    public function log()
    {
        return $this->morphOne(Activity::class, 'subject');
    }
}
