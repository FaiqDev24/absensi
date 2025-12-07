<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassRoom extends Model
{
     use HasFactory, SoftDeletes;

    protected $table = 'class_rooms';
    protected $fillable = ['name', 'schedule_hours'];

    public function students()
    {
        return $this->hasMany(Student::class, 'class_room_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'class_room_teacher');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_room_subject');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
