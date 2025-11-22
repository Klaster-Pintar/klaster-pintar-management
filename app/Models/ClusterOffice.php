<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClusterOffice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ihm_m_clusters_id',
        'name',
        'type_id',
        'location_point',
        'active_flag',
        'created_id',
        'updated_id',
        'deleted_id',
    ];

    protected $casts = [
        'active_flag' => 'boolean',
    ];

    public function getTable()
    {
        $this->table = env('TABLE_PREFIX', 'ihm_') . 'm_cluster_d_offices';
        return $this->table;
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class, 'ihm_m_clusters_id');
    }
}
