<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'roll_no',
        'reg_no',
        'dob',
        'gender',
        'blood_group',
        'class',
        'grade_id',
        'user_id',
    ];

    protected $casts = [
        'name' => 'string',
        'roll_no' => 'string',
        'reg_no' => 'string',
        'dob' => 'date',
        'gender' => 'string',
        'blood_group'=> 'string',
        'class' => 'string',
    ];

    public function grade(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
