<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CompanyController extends Controller
{
    public function index()
    {
        return CompanyResource::collection(Company::latest()->paginate());
    }

    public function myList()
    {
        return CompanyResource::collection(Company::where('user_id', Auth::user()->id)->latest()->paginate());
    }

    function list(Request $request) {
        $data = Company::latest()->all();
        return response([
            "data" => $data,
            "status" => 'ok',
            "success" => true,
            "message" => "success",
        ], 422); // return response()->json($data);
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
            "name" => ['string', 'required'],
            "email" => ['string', 'required'],
            "phone_number" => ['string'],
            "service" => ['string'],
            "country_id" => ['string'],
            "description" => ['string'],
        ]);

        if ($validator->fails()) {
            return response([
                "status" => 'failed',
                "success" => false,
                "error" => $validator->errors()->all(),
                "message" => $validator->errors()->all(),
                "data" => [],
            ], 422);
        }

        $service = Service::create([
            'name' => $request->service,
        ]);

        if ($service) {
            $data = Company::create(array_merge($request->all(),
            [
            'user_id' => Auth::user()->id, 
            'service_id' => $service->id
        ]));

        }

        return new CompanyResource($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $country
     * @return \Illuminate\Http\Response
     */
    public function show($company)
    {
            $data = Company::find($company);
        return new CompanyResource($data);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $company)
    {
        $validator = Validator::make($request->all(), [
            "name" => ['string'],
            "email" => ['string'],
            "phone_number" => ['string'],
            "service" => ['string'],
            "country_id" => ['string'],
            "description" => ['string'],
        ]);

        if ($validator->fails()) {
            return response([
                "status" => 'failed',
                "success" => false,
                "message" => $validator->errors()->all(),
                "error" => $validator->errors()->all(),
                "data" => [],
            ], 422);
        }
        $data = Company::find($company)->fill([
            "name" => $request->name,
            "email" => $request->email,
            "phone_number" => $request->phone_number,
            "country_id" => $request->country_id,
            "description" => $request->description,
        ])->save();

        
        return new CompanyResource(Company::findorfail($company));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();
    }
}
