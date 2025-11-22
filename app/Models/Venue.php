<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    use SoftDeletes;

    protected $fillable = ["name", "code", "address", "gmap_url", "waze_url"];

    /**
     * Get the user roles for the venue.
     */
    public function userRoles()
    {
        return $this->hasMany(UserRole::class);
    }

    /**
     * Get the users assigned to the venue.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles')
            ->withPivot('role', 'contact')
            ->withTimestamps();
    }

    /**
     * Get the capacities for the venue.
     */
    public function capacities()
    {
        return $this->hasMany(Capacity::class);
    }
}
