<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Daira extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'code', 'wilaya_id', 'name', 'name_ar'
    ];

    /**
     * Get the wilaya of the daira.
     */
    public function wilaya()
    {
        return $this->belongsTo("App\Wilaya");
    }

    /**
     * Get the communes for the daira.
     */
    public function communes()
    {
        return $this->hasMany("App\Commune");
    }
}
