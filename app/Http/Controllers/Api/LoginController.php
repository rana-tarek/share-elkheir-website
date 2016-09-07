<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\PayloadFactory;
use JWTFactory;
use Auth;
use Intervention\Image\ImageManagerStatic as Image;

class LoginController extends Controller
{
    public function generateGuestToken()
    {

        $guest = User::firstOrCreate(['name' => 'Guest', 'password' => md5('jan25'), 'email' => 'guest@guest.com']);
        // Generate JWT token
        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::fromUser($guest)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // if no errors are encountered we can return a JWT
        return response()->json(['token' => $token], 200);  
    }

    public function refreshToken(Request $request)
    {
        $token = $request->get('token');
        $newToken = JWTAuth::refresh($token);
        return response()->json(['token' => $newToken], 200);  
    }

    public function signup(Request $request)
    {
        $inputs = $request->all();
        $image = '';
        if($inputs['image'])
            $image = $this->upload($inputs['image'], $request->file('image'), 'uploads/users/');
        try { 
            $user = User::Create([
                'name'  => $inputs['name'],
                'email' => $inputs['email'],
                'image' => $image,
                'password' => md5($inputs['password'])
            ]);

            try {
            // verify the credentials and create a token for the user
                if (! $token = JWTAuth::fromUser($user)) {
                    return response()->json(['error' => 'invalid_credentials'], 401);
                }
            } catch (JWTException $e) {
                // something went wrong
                return response()->json(['error' => 'could_not_create_token'], 500);
            }

            // if no errors are encountered we can return a JWT
            return response()->json(['user' => $user, 'token' => $token], 200);
        } catch ( \Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
                return response()->json(['error' => 'Email is already taken'], 401);
            }
            else
                return response()->json(['error' => 'Something went wrong'], 500);
        }

        
    }

    public function getUser(Request $request)
    {
        $credentials = $request->only('access_token');
        try{
            $client = new \GuzzleHttp\Client();
            $url = 'https://graph.facebook.com/v2.5/me?access_token='.$credentials['access_token'].'&fields=name,email';
            $res = $client->request('GET', $url);
            $facebook_user = json_decode($res->getBody()->getContents());
        }
        catch(\GuzzleHttp\Exception\RequestException $e){
            return response()->json(['error' => $e], 401);
        }
        $user = User::where('users.fb_id', $facebook_user->id)->orWhere('users.email', $facebook_user->email)->first();
        if(!$user){
            $user = User::Create([
                'name'  => $facebook_user->name,
                'email' => $facebook_user->email,
                'image' => 'https://graph.facebook.com/'.$facebook_user->id.'/picture?type=large',
                'fb_id' => $facebook_user->id
            ]);
        }
        else{
            $user->image = 'https://graph.facebook.com/'.$facebook_user->id.'/picture?type=large';
            $user->fb_id = $facebook_user->id;
            $user->save();
        }

        // Generate JWT token
        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::fromUser($user)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // if no errors are encountered we can return a JWT
        return response()->json(['user' => $user, 'token' => $token], 200);
    }

    public function login(Request $request)
    {
        // validate the info, create rules for the inputs
        $rules = array(
            'email'    => 'required|email', // make sure the email is an actual email
            'password' => 'required|min:4' // password can only be alphanumeric and has to be greater than 3 characters
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return response()->json(['error' => $validator], 200); 
        } else {
            $inputs = $request->all();
            $user = User::where('email', '=' ,$inputs['email'])->where('password', '=', md5($inputs['password']))->first();
            if(!$user)
                return response()->json(['error' => 'invalid_credentials'], 401);
            $user->image = url('uploads/users/').'/'.$user->image;
            // Generate JWT token
            try {
                    // verify the credentials and create a token for the user
                    if (! $token = JWTAuth::fromUser($user)) {
                        return response()->json(['error' => 'invalid_credentials'], 401);
                    }
                } catch (JWTException $e) {
                    // something went wrong
                    return response()->json(['error' => 'could_not_create_token'], 500);
                }

                // if no errors are encountered we can return a JWT
                return response()->json(['user' => $user, 'token' => $token], 200);
        }
    }
    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        // the token is valid and we have found the user via the sub claim
        return $user->id;
    }

    public function upload($image, $file, $dir)
    {
        $file_name = date('YmdHis').'.'.$file->getClientOriginalExtension();
        $image = Image::make($image->getRealPath());
        $image->fit(700, 1000) ->save($dir.'image-'.$file_name);
        return $file_name;
    }
}
