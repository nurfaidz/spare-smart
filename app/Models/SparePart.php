<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Activity;

class SparePart extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function brand()
    {
        return $this->belongsTo(Brand::class)->withTrashed();
    }

    public function logs()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function prices()
    {
        return $this->hasMany(SparePartPrice::class);
    }
}
