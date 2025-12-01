<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClusterResident extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ihm_m_clusters_id',
        'resident_id',
        'created_id',
        'updated_id',
        'deleted_id',
    ];

    public function getTable()
    {
        $this->table = env('TABLE_PREFIX', 'ihm_') . 'm_cluster_d_residents';
        return $this->table;
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class, 'ihm_m_clusters_id');
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class, 'resident_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'resident_id');
    }
}
