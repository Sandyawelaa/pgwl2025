<?php

namespace App\Http\Controllers;

use App\Models\PolygonModel;
use Illuminate\Http\Request;

class PolygonController extends Controller
{

    public function __construct()
    {
        $this->polygon = new PolygonModel;
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
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        //Get image file
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polygon." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }
        $data = [
            'geom' => $request->geom_polygon,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
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
        ];

        return view('edit-polygonn', $data);
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
        $old_image = $polygon->image;

        //Handle image upload
        if ($request->hasFile('image')) {
            //Delete old image
            if ($old_image != null && file_exists('./storage/images/' . $old_image)) {
                unlink('./storage/images/' . $old_image);
            }

            //Upload new image
            $image = $request->file('image');
            $name_image = time() . "_polygon." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = $old_image;
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
        $imagefile = $this->polygon->find($id)->image;

        if (!$this->polygon->destroy($id)){
            return redirect()->route('map')->with('error', 'Polygon Failed to delete');
        }

        //Delete image file
        if ($imagefile != null) {
            if (file_exists('./storage/images/' .$imagefile)) {
                unlink('./storage/images/' .$imagefile);
            }
        }

        return redirect()->route('map')->with('success', 'Polygon has been deleted');
        }
    }
