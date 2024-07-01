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
        return $this->belongsTo(Brand::class);
    }

    public function logs()
    {
        return $this->hasMany(Activity::class,'subject_id');
    }

    public function prices()
    {
        return $this->hasMany(SparePartPrice::class);
    }
}
