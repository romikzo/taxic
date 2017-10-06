<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'person';
    public $timestamps = false;

    public function coordinate()
    {
        return $this->hasOne('App\Coordinate', 'person_id');
    }
}
