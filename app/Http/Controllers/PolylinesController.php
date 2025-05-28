<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PolylinesModel;

class PolylinesController extends Controller
{

    public function __construct()
    {
        $this->polylines = new PolylinesModel;
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
                'name' => 'required|unique:polylines,name',
                'description' => 'required',
                'geom_polyline' => 'required',
                'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'description.required' => 'Description is required',
                'geom_polyline.required' => 'Location is required',
            ]
        );
        //Create images directory if not exists
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        //Get image file
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polyline." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }

        $data = [
            'geom' => $request->geom_polyline,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
            'user_id' => auth()->user()->id,
        ];

        //Create Data
        if (!$this->polylines->create($data)) {
            return redirect()->route('map')->with('error', 'Polyline Failed to add');
        }

        //Redirect to map
        return redirect()->route('map')->with('success', 'Polyline has been added');
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
            'title' => 'Edit Polyline',
            'id' => $id,
        ];
        return view('edit-polyline', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //Validation
        $request->validate(
            [
                'name' => 'required|unique:polylines,name,'.$id,
                'description' => 'required',
                'geom_polyline' => 'required',
                'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'description.required' => 'Description is required',
                'geom_polyline.required' => 'Location is required',
            ]
        );

        //Get old data
        $polyline = $this->polylines->find($id);
        $old_image = $polyline->image;

        //Handle image upload
        if ($request->hasFile('image')) {
            //Delete old image
            if ($old_image != null && file_exists('./storage/images/' . $old_image)) {
                unlink('./storage/images/' . $old_image);
            }

            //Upload new image
            $image = $request->file('image');
            $name_image = time() . "_polyline." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = $old_image;
        }

        //Update data
        $data = [
            'geom' => $request->geom_polyline,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        if (!$polyline->update($data)) {
            return redirect()->route('map')->with('error', 'Polyline Failed to update');
        }

        return redirect()->route('map')->with('success', 'Polyline has been updated');
    }

    /**
    * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $imagefile = $this->polylines->find($id)->image;

        if (!$this->polylines->destroy($id)){
            return redirect()->route('map')->with('error', 'Polyline Failed to delete');
        }

        //Delete image file
        if ($imagefile != null) {
            if (file_exists('./storage/images/' .$imagefile)) {
                unlink('./storage/images/' .$imagefile);
            }
        }

        return redirect()->route('map')->with('success', 'Polyline has been deleted');
        }
    }
