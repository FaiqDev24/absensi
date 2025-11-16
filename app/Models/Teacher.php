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
        'subject_id',
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
        return $this->belongsTo(Subject::class,'subject_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'id_user');
    }
}
