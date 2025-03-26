<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PolygonModel extends Model
{
    protected $table = 'polygon';

    protected $guarded = ['id'];

    public function geojson_polygon()
    {
        $polygon = $this
            ->select(columns: DB::raw('st_asgeojson(geom) as geom, name, description, st_area(geom, true) as luas_m2,
st_area (geom, true)/1000000 as luas_km2, st_area(geom, true)/10000 as luas_hektar, created_at, updated_at'))
            ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($polygon as $polygon) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($polygon->geom),
                'properties' => [
                    'name' => $polygon->name,
                    'description' => $polygon->description,
                    'luas_m2' => $polygon->luas_m2,
                    'luas_km2' => $polygon->luas_km2,
                    'luas_hektar' => $polygon->luas_hektar,
                    'created_at' => $polygon->created_at,
                    'updated_at' => $polygon->updated_at,
                ],
            ];

            array_push($geojson['features'], $feature);
        }
        return $geojson;
}
}
