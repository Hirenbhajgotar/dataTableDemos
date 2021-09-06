<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
    protected $fillable = [
        'state',
        'country_id'
    ];
    // protected $with = array('Student');

    public function Student()
    {
        return $this->hasMany(Student::class);
    }

    public function Country()
    {
        return $this->belongsTo(Country::class);
    }
}
