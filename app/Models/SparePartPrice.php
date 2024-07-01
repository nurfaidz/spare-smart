<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePartPrice extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function sparePart()
    {
        return $this->belongsTo(SparePart::class);
    }
}
