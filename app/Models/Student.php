<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
     use HasFactory, SoftDeletes;

    protected $fillable = ['nis', 'name', 'username', 'password', 'gender', 'class_room_id', 'grade'];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class);
    }
}
