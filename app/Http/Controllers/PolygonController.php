<?php

namespace App\Http\Controllers;

use App\Models\PolygonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PolygonController extends Controller
{
    protected $polygon;

    public function __construct()
    {
        $this->polygon = new PolygonModel();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Map',
        ];

        return view('map', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validation
        $request->validate(
            [
                'name' => 'required|unique:polygon,name',
                'description' => 'required',
                'geom_polygon' => 'required',
                'image' => 'nullable|mimes:jpg,jpeg,png|max:1024',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'description.required' => 'Description is required',
                'geom_polygon.required' => 'Location is required',
            ]
        );

        //Create images directory if not exists
        if (!file_exists(storage_path('app/public/images'))) {
            mkdir(storage_path('app/public/images'), 0777, true);
        }

        //Get image file
        $name_image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polygon." . strtolower($image->getClientOriginalExtension());
            $image->storeAs('public/images', $name_image);
        }

        $data = [
            'geom' => $request->geom_polygon,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
            'user_id' => auth()->id(),
        ];

        //Create Data
        if (!$this->polygon->create($data)) {
            return redirect()->route('map')->with('error', 'Polygon Failed to add');
        }

        //Redirect to map
        return redirect()->route('map')->with('success', 'Polygon has been added');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = [
            'title' => 'Edit Polygon',
            'id' => $id,
            'polygon' => $this->polygon->find($id)
        ];

        return view('edit-polygon', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //Validation
        $request->validate(
            [
                'name' => 'required|unique:polygon,name,'.$id,
                'description' => 'required',
                'geom_polygon' => 'required',
                'image' => 'nullable|mimes:jpg,jpeg,png|max:1024',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'description.required' => 'Description is required',
                'geom_polygon.required' => 'Location is required',
            ]
        );

        //Get old data
        $polygon = $this->polygon->find($id);
        if (!$polygon) {
            return redirect()->route('map')->with('error', 'Polygon not found');
        }

        $old_image = $polygon->image;
        $name_image = $old_image;

        //Handle image upload
        if ($request->hasFile('image')) {
            //Delete old image if exists
            if ($old_image && Storage::exists('public/images/' . $old_image)) {
                Storage::delete('public/images/' . $old_image);
            }

            //Upload new image
            $image = $request->file('image');
            $name_image = time() . "_polygon." . strtolower($image->getClientOriginalExtension());
            $image->storeAs('public/images', $name_image);
        }

        //Update data
        $data = [
            'geom' => $request->geom_polygon,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        if (!$polygon->update($data)) {
            return redirect()->route('map')->with('error', 'Polygon Failed to update');
        }

        return redirect()->route('map')->with('success', 'Polygon has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $polygon = $this->polygon->find($id);
        if (!$polygon) {
            return redirect()->route('map')->with('error', 'Polygon not found');
        }

        $imagefile = $polygon->image;

        if (!$polygon->delete()) {
            return redirect()->route('map')->with('error', 'Polygon Failed to delete');
        }

        //Delete image file if exists
        if ($imagefile && Storage::exists('public/images/' . $imagefile)) {
            Storage::delete('public/images/' . $imagefile);
        }

        return redirect()->route('map')->with('success', 'Polygon has been deleted');
    }
}
