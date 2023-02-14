<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'course_id', 'user_id','rating', 'note'
    ];

    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];
}
