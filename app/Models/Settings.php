<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'icon',
        'description',
        'status',
        'alt_text',
    ];

    protected $appends = ['logo_path','icon_path'];

    public function getLogoPathAttribute()
    {
        return asset("uploads/settings/" . $this->logo);
    }
    public function getIconPathAttribute()
    {
        return asset("uploads/settings/" . $this->icon);
    }
}
