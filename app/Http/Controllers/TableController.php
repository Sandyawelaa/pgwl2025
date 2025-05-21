<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use App\Models\PointsModel;
use App\Models\Polygonodel;
use App\Models\PolygonModel;
use Illuminate\Http\Request;
use App\Models\PolylinesModel;

class TableController extends Controller
{
    public function __construct()
    {
        $this->points = new PointsModel();
        $this->polylines = new PolylinesModel();
        $this->polygon = new PolygonModel();
    }
    public function index()
    {
        $data = [
            'title' => 'Table',
            'points' => $this->points->get(),
            'polylines' => $this->polylines->get(),
            'polygons' => $this->polygon->get()
        ];

        return view('table', $data);
    }
}
