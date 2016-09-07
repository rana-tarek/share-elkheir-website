<?php

namespace App\Http\Controllers\Api;

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

class ContributionController extends Controller
{

    public function add(Request $request)
    {

        $inputs = $request->all();
        $contribution = Contribution::Create([
            'user_id' => $inputs['user_id'],
            'type_of_contribution' => $inputs['type_of_contribution'],
            'quantity' => $inputs['quantity'],
            'access' => $inputs['access'],
            'covered' => $inputs['covered'],
            'latitude' => $inputs['latitude'],
            'longitude' => $inputs['longitude'],
            'area' => $inputs['area']
            ]);
        return response()->json(['status' => 'success'], 200); 
    }

    public function getUsersContributions(Request $request)
    {
        $inputs = $request->all();
        $offset = $inputs['offset'];
        $contributions = Contribution::where('user_id', '=', $inputs['user_id'])->orderBy('created_at', 'desc')->skip($offset)->take(10)->get();
        if(count($contributions) > 0)
            return response()->json(['contributions' => $contributions], 200);
        else
            return response()->json(['success' => 'No contributions available'], 200);
    }

    public function getLatestContribution(Request $request)
    {
        $inputs = $request->all();
        $limit = 500 / $inputs['zoom_level'];
        if($inputs['lng1'] >= 0 && $inputs['lng2'] < 0)
        {
            $time = Contribution::where(function($query) use ($inputs) {
                $query->whereBetween('longitude', [$inputs['lng1'], 180])
                    ->orWhereBetween('longitude', [180, $inputs['lng2']]);
            })->whereBetween('latitude', [$inputs['lat1'], $inputs['lat2']])->whereBetween('longitude', [$inputs['lng1'], $inputs['lng2']])->orderBy('created_at', 'desc')->take($limit)->first();
        }
        elseif($inputs['lng2'] < $inputs['lng1'])
            $time = Contribution::whereBetween('latitude', [$inputs['lat2'], $inputs['lat1']])->whereBetween('longitude', [$inputs['lng2'], $inputs['lng1']])->orderBy('created_at', 'desc')->take($limit)->first();
        else
            $time = Contribution::whereBetween('latitude', [$inputs['lat1'], $inputs['lat2']])->whereBetween('longitude', [$inputs['lng1'], $inputs['lng2']])->orderBy('created_at', 'desc')->take($limit)->first();

        if(count($time) > 0)
            return response()->json(['time' => $time->created_at->diffForHumans()], 200);
        else
            return response()->json(['success' => 'No contributions available in this area'], 200);
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
            return response()->json(['contributions' => $contributions], 200);
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
            return response()->json(['contributions' => $contributions], 200);
        else
            return response()->json(['success' => 'No contributions available in this area.'], 200);
    }

}
