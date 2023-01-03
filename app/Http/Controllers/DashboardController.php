<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Http\Resources\CompanyResource;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $total = Company::query()->where('user_id', $user->id)->count();

        $latest = Company::query()->where('user_id', $user->id)->latest('created_at')->first();

        $nlatest = ["data" => $latest];
        return [
            'totalCompanies' => $total,
            'latestSurvey' => $latest ? new CompanyResource($latest) : null,
            'totalAnswers' => 0,
            'latestAnswers' => 0
        ];
    }
}
