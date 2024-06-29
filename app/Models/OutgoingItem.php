<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;

class OutgoingItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reportOutgoingItem()
    {
        return $this->morphOne(Report::class, 'reportable');
    }

    public function logs()
    {
        return $this->hasMany(Activity::class, 'subject_id');
    }
}
