<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PolylinesModel extends Model
{
    protected $table = 'polylines';

    protected $guarded = ['id'];

    public function geojson_polylines()
    {
        $polylines = $this
            ->select(DB::raw('polylines.id, ST_AsGeoJSON(polylines.geom) as geom, 
                polylines.name, polylines.description, polylines.image, 
                polylines.created_at, polylines.updated_at, 
                polylines.user_id, users.name as user_created,
                ST_Length(polylines.geom, true) as length_m,
                ST_Length(polylines.geom, true)/1000 as length_km'))
            ->leftJoin('users', 'polylines.user_id', '=', 'users.id')
            ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($polylines as $polyline) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($polyline->geom),
                'properties' => [
                    'id' => $polyline->id,
                    'name' => $polyline->name,
                    'description' => $polyline->description,
                    'length_m' => round($polyline->length_m, 2),
                    'length_km' => round($polyline->length_km, 2),
                    'created_at' => $polyline->created_at,
                    'updated_at' => $polyline->updated_at,
                    'image' => $polyline->image,
                    'user_id' => $polyline->user_id,
                    'user_created' => $polyline->user_created
                ],
            ];
            array_push($geojson['features'], $feature);
        }
        return $geojson;
    }

    public function geojson_polyline($id)
    {
        $polylines = $this
            ->select(DB::raw('polylines.id, ST_AsGeoJSON(polylines.geom) as geom, 
                polylines.name, polylines.description, polylines.image,
                ST_Length(polylines.geom, true) as length_m,
                ST_Length(polylines.geom, true)/1000 as length_km,
                polylines.created_at, polylines.updated_at,
                polylines.user_id, users.name as user_created'))
            ->leftJoin('users', 'polylines.user_id', '=', 'users.id')
            ->where('polylines.id', $id)
            ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($polylines as $polyline) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($polyline->geom),
                'properties' => [
                    'id' => $polyline->id,
                    'name' => $polyline->name,
                    'description' => $polyline->description,
                    'length_m' => round($polyline->length_m, 2),
                    'length_km' => round($polyline->length_km, 2),
                    'created_at' => $polyline->created_at,
                    'updated_at' => $polyline->updated_at,
                    'image' => $polyline->image,
                    'user_id' => $polyline->user_id,
                    'user_created' => $polyline->user_created
                ],
            ];
            array_push($geojson['features'], $feature);
        }
        return $geojson;
    }
}
