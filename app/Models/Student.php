<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'document', 'birth_date', 'phone', 'email', 'created_by', 'updated_by', 'deleted_by'];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date'
        ];
    }

    public function address(): HasOne
    {
        return $this->hasOne(Address::class, 'student_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(ClassModel::class, 'enrollments', 'student_id', 'class_id')->withTimestamps();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');;
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');;
    }

    protected static function booted(): void
    {
        static::creating(function (Student $student) {
            $student->created_by = auth()->id();
            $student->updated_by = auth()->id();
        });

        static::saving(function (Student $student) {
            $student->updated_by = auth()->id();
        });
    }
}
