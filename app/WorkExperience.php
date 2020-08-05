<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'work_experience', 'start_date', 'end_date'
    ];

//    protected $hidden = [
//        'user_id'
//    ];
}
