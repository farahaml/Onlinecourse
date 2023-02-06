<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageCourse extends Model
{
    protected $table = 'image_courses';

    protected $fillable = ['image_id', 'course_id'];

}
