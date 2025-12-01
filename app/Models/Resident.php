<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resident extends Model
{
    use HasFactory, SoftDeletes;

    protected $table;

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
        'rt',
        'rw',
        'house_block',
        'house_number',
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

    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_birth' => 'date',
        'suspend_expired_at' => 'datetime',
        'roles' => 'array',
        'device' => 'array',
        'active_flag' => 'boolean',
        'blocked' => 'boolean',
    ];

    public function getTable()
    {
        if (!isset($this->table)) {
            $this->table = env('TABLE_PREFIX', 'ihm_') . 'm_residents';
        }
        return $this->table;
    }

    /**
     * Get cluster residents
     */
    public function clusterResidents()
    {
        return $this->hasMany(ClusterResident::class, 'resident_id');
    }
}
