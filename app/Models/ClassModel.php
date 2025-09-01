<?php

namespace App\Models;

use App\Enums\ClassModality;
use App\Enums\ClassStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassModel extends Model
{
    /** @use HasFactory<\Database\Factories\ClassModelFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'classes';
    protected $fillable = ['name', 'code', 'status', 'start_date', 'end_date', 'capacity', 'modality', 'created_by', 'updated_by'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'status' => ClassStatus::class,
            'modality' => ClassModality::class,
        ];
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'class_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'enrollments', 'class_id', 'student_id')->withTimestamps();
    }

    public function monitors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_user', 'class_id', 'user_id')
            ->withPivot('role_in_class')
            ->withTimestamps();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected static function booted(): void
    {
        static::creating(function (ClassModel $class) {
            $class->created_by = auth()->id();
            $class->updated_by = auth()->id();
        });

        static::saving(function (ClassModel $class) {
            $class->updated_by = auth()->id();
        });
    }
}
