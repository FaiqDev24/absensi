<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'subject_teacher');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function classRooms()
    {
        return $this->belongsToMany(ClassRoom::class, 'class_room_subject');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
