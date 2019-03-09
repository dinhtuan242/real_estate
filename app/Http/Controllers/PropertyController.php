<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\District;
use App\Models\PropertyType;
use App\Models\Province;
use App\Models\PropertyCategory;
use App\Models\Unit;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use App\Repositories\PropertyRepository;
use App\Repositories\PropertyImageRepository;
use App\Http\Requests\PropertyRequest;

class PropertyController extends Controller
{
    protected $province;

    public function __construct(PropertyRepository $properties, PropertyImageRepository $image)
    {
        $this->property = $properties;
        $this->property_image = $image;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();

        $unit = Unit::all();

        $province = Province::all();

        $district = District::all();

        $propertyCategory = PropertyCategory:: all();

        $propertyType = PropertyType::all();
        
        return view('fontend.properties.property_submit', compact('user', 'province', 'district', 'propertyCategory', 'propertyType', 'unit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PropertyRequest $request)
    {
        $request->merge([
            'status' => 0,
            'user_id' => $request->user()->id,
        ]);
        $properties = $this->property->create($request->all());
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $item) {
                $name = $item->getClientOriginalName();
                $image = str_random(5) . $name;
                while (file_exists('upload/property' . $image))
                {
                    $image = str_random(5) . '.' . $image;
                }
                $item->move(config('app.property_path'), $image); 
                $file = [
                    'property_id' => $properties->id,
                    'link' => $image,
                ];
                $image = $this->property_image->create($file);
            }
            
            return redirect('property')->with('message', __('message.add_property'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try
        {
            $properties = Property::with('propertyImage')->where('user_id', $id)->paginate(config('pagination.myProperty'));

            return view('fontend.properties.my_property', compact('properties'));
        }
        catch (ModelNotFoundException $ex)
        {
            echo $ex->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
