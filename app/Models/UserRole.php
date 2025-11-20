<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRole extends Model
{
    use SoftDeletes;

    protected $fillable = ["user_id", "venue_id", "role", "contact"];

    /**
     * Get the user that owns the user role.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the venue that the user role belongs to.
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}
