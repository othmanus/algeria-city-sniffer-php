<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wilaya extends Model
{

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'code', 'name', 'name_ar'
    ];

    /**
     * Get all dairas for the wilaya.
     */
    public function dairas()
    {
        return $this->hasMany("App\Daira");
    }
}
