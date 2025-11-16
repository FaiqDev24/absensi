<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassRoom extends Model
{
     use HasFactory, SoftDeletes;

    protected $table = 'class_rooms';
    protected $fillable = ['name'];

    public function students()
    {
        return $this->hasMany(Student::class, 'class_room_id');
    }
}
