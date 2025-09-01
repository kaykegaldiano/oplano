<?php

namespace App\Models;

use App\Enums\EnrollmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    /** @use HasFactory<\Database\Factories\EnrollmentFactory> */
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_id',
        'status',
        'enrolled_at',
        'canceled_at',
        'cancel_reason',
        'completed_at',
        'created_by',
        'updated_by'
    ];

    protected function casts(): array
    {
        return [
            'enrolled_at' => 'datetime',
            'canceled_at' => 'datetime',
            'completed_at' => 'datetime',
            'status' => EnrollmentStatus::class
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
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
        static::creating(function (Enrollment $enrollment) {
            $enrollment->created_by = auth()->id();
            $enrollment->updated_by = auth()->id();
        });

        static::saving(function (Enrollment $enrollment) {
            $enrollment->updated_by = auth()->id();
        });
    }
}
