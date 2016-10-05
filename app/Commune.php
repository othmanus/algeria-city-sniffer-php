<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'code', 'daira_id', 'name', 'name_ar'
    ];

    /**
     * Get the daira of the commune.
     */
    public function daira()
    {
        return $this->belongsTo("App\Daira");
    }

}
