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

    /**
     * Get latitude from POINT geometry
     */
    public function getLatitudeAttribute()
    {
        if (!$this->location_point) {
            return null;
        }

        // Extract latitude from POINT(longitude, latitude) format
        // POINT is stored as binary, need to use ST_AsText to get coordinates
        $point = \DB::selectOne(
            "SELECT ST_Y(location_point) as lat FROM {$this->getTable()} WHERE id = ?",
            [$this->id]
        );

        return $point ? $point->lat : null;
    }

    /**
     * Get longitude from POINT geometry
     */
    public function getLongitudeAttribute()
    {
        if (!$this->location_point) {
            return null;
        }

        // Extract longitude from POINT(longitude, latitude) format
        $point = \DB::selectOne(
            "SELECT ST_X(location_point) as lng FROM {$this->getTable()} WHERE id = ?",
            [$this->id]
        );

        return $point ? $point->lng : null;
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class, 'ihm_m_clusters_id');
    }
}
