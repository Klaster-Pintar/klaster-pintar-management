<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        if (!isset($this->table)) {
            $this->table = env('TABLE_PREFIX', '') . 'm_users';
        }

        return $this->table;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'gender',
        'date_birth',
        'address',
        'province_id',
        'regency_id',
        'role',
        'roles',
        'avatar',
        'email_verified_at',
        'otp_code',
        'password',
        'device',
        'active_flag',
        'status',
        'suspend',
        'suspend_expired_at',
        'blocked',
        'blocked_id',
        'created_id',
        'updated_id',
        'deleted_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
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
            'date_birth' => 'date',
            'suspend_expired_at' => 'datetime',
            'roles' => 'array',
            'device' => 'array',
            'active_flag' => 'boolean',
            'blocked' => 'boolean',
        ];
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->active_flag && !$this->blocked && $this->suspend == 0;
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool
    {
        if (is_array($this->roles)) {
            return in_array($role, $this->roles);
        }
        return $this->role === $role;
    }
}
