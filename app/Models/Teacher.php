<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id_user',
        'nip',
        'username',
        'password',
        'subject_id',
        'teaching_hours',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
    protected $hidden = [
        'password',
    ];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'id_user');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function classRooms()
    {
        return $this->belongsToMany(ClassRoom::class, 'class_room_teacher');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
