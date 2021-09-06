<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone',
        'email',
        'gender',
        'country_id',
        'state_id',
        'profile_photo_path',
        'term_condition'
    ];
    
    public function State() 
    {
        return $this->belongsTo(State::class);
    }
}
