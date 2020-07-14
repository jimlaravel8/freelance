<?php

namespace Platform\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;


class Promo extends Model
{
    public function user()
    {
        return $this->belongsTo(user::class);
    }
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
