<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
     use HasFactory, SoftDeletes;

    protected $fillable = ['id_user', 'nis', 'gender', 'class_room_id', 'grade', 'attendance'];

    protected $casts = [
        
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Accessor untuk hitung total kehadiran dari history
    public function getTotalAttendanceAttribute()
    {
        return $this->attendances()->where('status', 'hadir')->count();
    }
}
