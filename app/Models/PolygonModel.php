<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PolygonModel extends Model
{
    use HasFactory;
    protected $table = 'polygon';
    protected $guarded = ['id'];

    // Append image URL to the model
    protected $appends = ['image_url'];

    // Get image URL attribute
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/images/' . $this->image);
        }
        return null;
    }

    public function geojson_polygon()
    {
        $polygons = $this
            ->select(DB::raw('polygon.id, ST_AsGeoJSON(polygon.geom) as geom, polygon.name, polygon.description, 
                polygon.image, polygon.created_at, polygon.updated_at, polygon.user_id, users.name as user_created,
                ST_Area(polygon.geom, true) as luas_m2,
                ST_Area(polygon.geom, true)/1000000 as luas_km2,
                ST_Area(polygon.geom, true)/10000 as luas_hektar'))
            ->leftJoin('users', 'polygon.user_id', '=', 'users.id')
            ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($polygons as $poly) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($poly->geom),
                'properties' => [
                    'id' => $poly->id,
                    'name' => $poly->name,
                    'description' => $poly->description,
                    'image' => $poly->image,
                    'luas_m2' => round($poly->luas_m2, 2),
                    'luas_km2' => round($poly->luas_km2, 2),
                    'luas_hektar' => round($poly->luas_hektar, 2),
                    'created_at' => $poly->created_at,
                    'updated_at' => $poly->updated_at,
                    'user_id' => $poly->user_id,
                    'user_created' => $poly->user_created
                ],
            ];

            array_push($geojson['features'], $feature);
        }
        return $geojson;
    }

    public function geojson_polygonn($id)
    {
        $polygon = $this
            ->select(DB::raw('id, ST_AsGeoJSON(geom) as geom, name, description, image,
                ST_Area(geom, true) as luas_m2,
                ST_Area(geom, true)/1000000 as luas_km2,
                ST_Area(geom, true)/10000 as luas_hektar,
                created_at, updated_at, user_id'))
        ->where('id', $id)
        ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($polygon as $poly) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($poly->geom),
                'properties' => [
                    'id' => $poly->id,
                    'name' => $poly->name,
                    'description' => $poly->description,
                    'luas_m2' => round($poly->luas_m2, 2),
                    'luas_km2' => round($poly->luas_km2, 2),
                    'luas_hektar' => round($poly->luas_hektar, 2),
                    'created_at' => $poly->created_at,
                    'updated_at' => $poly->updated_at,
                    'image' => $poly->image,
                    'user_id' => $poly->user_id
                ],
            ];
            array_push($geojson['features'], $feature);
        }
        return $geojson;
    }
}
