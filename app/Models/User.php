<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function observations(): HasMany
    {
        return $this->hasMany(Observation::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin_global';
    }

    public function isCS(): bool
    {
        return $this->role === 'customer_success';
    }

    public function isMonitor(): bool
    {
        return $this->role === 'monitor';
    }

    public function monitoredClasses(): BelongsToMany
    {
        return $this->belongsToMany(ClassModel::class, 'class_user', 'user_id', 'class_id')
            ->withPivot('role_in_class')
            ->wherePivot('role_in_class', 'monitor')
            ->withTimestamps();
    }

    #[Scope]
    protected function onlyMonitorEnrollments(Builder $query): Builder
    {
        $classIds = $this->monitoredClasses()->pluck('classes.id');
        return Enrollment::query()->whereIn('class_id', $classIds);
    }
}
