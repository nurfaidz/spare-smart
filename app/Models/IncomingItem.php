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

    public function log()
    {
        return $this->morphOne(Activity::class, 'subject');
    }

    public function sparePart()
    {
        return $this->belongsTo(SparePart::class)->withTrashed();
    }
}
