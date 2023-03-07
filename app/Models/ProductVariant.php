<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Variant;

class ProductVariant extends Model
{
    public function varient()
    {
        return $this->hasOne(Variant::class);
    }
}
