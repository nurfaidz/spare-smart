<?php

namespace App\Models;

use App\States\Status\StatusState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Activity;
use Spatie\ModelStates\HasStates;

class OutgoingItem extends Model
{
    use HasFactory, SoftDeletes, HasStates;

    protected $guarded = [];

    protected $casts = [
        'status' => StatusState::class,
    ];

    public function reportOutgoingItem()
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
