<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClusterSecurity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ihm_m_clusters_id',
        'security_id',
        'created_id',
        'updated_id',
        'deleted_id',
    ];

    public function getTable()
    {
        $this->table = env('TABLE_PREFIX', 'ihm_') . 'm_cluster_d_securities';
        return $this->table;
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class, 'ihm_m_clusters_id');
    }

    public function security()
    {
        return $this->belongsTo(User::class, 'security_id');
    }
}
