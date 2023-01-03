<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ["data" => Country::latest()->get()];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
	    	"name" => ['string','required']
        ]);

        if ($validator->fails()) {
            return response([
                "status" => 'failed',
                "success" => false,
                "message" => $validator->errors()->all(),
                "data" => []
            ],Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $data = Country::create($request->all());
        return response([
            "status" => 'ok',
            "success" => true,
            "message" => "Country added succesfully",
            "data" => $data
        ],Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show($country)
    {
        $data = Country::with('states')->where('id',$country)->get();
        return response([
            "data" => $data,
            "status" => "ok",
            "success" => true,
            "message" => "success"
        ],Response::HTTP_OK);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $country)
    {
        $validator = Validator::make($request->all(), [
	    	"name" => ['string']
        ]);

        if ($validator->fails()) {
            return response([
                "status" => 'failed',
                "success" => false,
                "message" => $validator->errors()->all(),
                "data" => []
            ],Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $data = Country::where('id', $country)->update($request->except('id')); 
                         
        return response([
            "data" => Country::find($country),
            "status" => 'ok',
            "success" => true,
            "message" => "Country Updated Succesfully"
        ],Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        $country->delete();
        return response([
            "data" => [],
            "status" => 'ok',
            "success" => true,
            "message" => "Country deleted successfully"
        ],Response::HTTP_OK);
    }
}
