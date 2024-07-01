<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;

class IncomingItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reportIncomingItem()
    {
        return $this->morphOne(Report::class, 'reportable');
    }

    public function logs()
    {
        return $this->hasMany(Activity::class, 'subject_id');
    }

    public function sparePart()
    {
        return $this->belongsTo(SparePart::class);
    }
}
