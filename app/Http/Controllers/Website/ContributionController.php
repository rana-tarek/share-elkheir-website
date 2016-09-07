<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use App\User;
use App\Contribution;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\PayloadFactory;
use JWTFactory;
use Auth;
use DateDiff;

class ContributionController extends Controller
{
    public function index ()
    {
         return view('website.contribution');   
    }
    public function getContributionsByArea(Request $request)
    {
        $inputs = $request->all();
        $limit = 500 / $inputs['zoom_level'];
        if($inputs['lng1'] >= 0 && $inputs['lng2'] < 0)
        {
            $contributions = Contribution::where(function($query) use ($inputs) {
                $query->whereBetween('longitude', [$inputs['lng1'], 180])
                    ->orWhereBetween('longitude', [180, $inputs['lng2']]);
            })->whereBetween('latitude', [$inputs['lat1'], $inputs['lat2']])->whereBetween('longitude', [$inputs['lng1'], $inputs['lng2']])->orderBy('created_at', 'desc')->take($limit)->with('user')->get();
        }
        elseif($inputs['lng2'] < $inputs['lng1'])
            $contributions = Contribution::whereBetween('latitude', [$inputs['lat2'], $inputs['lat1']])->whereBetween('longitude', [$inputs['lng2'], $inputs['lng1']])->orderBy('created_at', 'desc')->take($limit)->with('user')->get();
        else
            $contributions = Contribution::whereBetween('latitude', [$inputs['lat1'], $inputs['lat2']])->whereBetween('longitude', [$inputs['lng1'], $inputs['lng2']])->orderBy('created_at', 'desc')->take($limit)->with('user')->get();

        if(count($contributions) > 0)
        {
            $time = $contributions->first();
            $time = DateDiff::inArabic($time->created_at);
            return response()->json(['contributions' => $contributions, 'time' => $time], 200);
        }
        else
            return response()->json(['success' => 'No contributions available in this area.'], 200);
    }

    public function getAccessByArea(Request $request)
    {
        $inputs = $request->all();
        $limit = 500 / $inputs['zoom_level'];
        if($inputs['lng1'] >= 0 && $inputs['lng2'] < 0)
        {
            $contributions = Contribution::where(function($query) use ($inputs) {
                $query->whereBetween('longitude', [$inputs['lng1'], 180])
                    ->orWhereBetween('longitude', [180, $inputs['lng2']]);
            })->whereBetween('latitude', [$inputs['lat1'], $inputs['lat2']])->whereBetween('longitude', [$inputs['lng1'], $inputs['lng2']])->orderBy('created_at', 'desc')->take($limit)->with('user')->get();
        }
        elseif($inputs['lng2'] < $inputs['lng1'])
            $contributions = Contribution::whereBetween('latitude', [$inputs['lat2'], $inputs['lat1']])->whereBetween('longitude', [$inputs['lng2'], $inputs['lng1']])->orderBy('created_at', 'desc')->take($limit)->with('user')->get();
        else
            $contributions = Contribution::whereBetween('latitude', [$inputs['lat1'], $inputs['lat2']])->whereBetween('longitude', [$inputs['lng1'], $inputs['lng2']])->orderBy('created_at', 'desc')->take($limit)->with('user')->get();

        if(count($contributions) > 0)
        {
            return response()->json(['contributions' => $contributions], 200);
        }
        else
            return response()->json(['success' => 'No contributions available in this area.'], 200);
    }

}
