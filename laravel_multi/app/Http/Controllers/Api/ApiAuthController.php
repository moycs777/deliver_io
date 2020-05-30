<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;

class ApiAuthController extends Controller
{
    // $token = auth()->tokenById(123);
    // $token = auth()->claims(['foo' => 'bar'])->attempt($credentials);

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'signup']]);
    }
    
    public function socialStart(){

    }

    public function signup(Request $request)
    {
        
        if($request->login_type == 'email'){
            
            $validator = Validator::make($request->all(), [
                'name'=>'required',
                'email'=>'required|email|unique:users',
            ]);
            
            if ($validator->fails()) {
                return response()->json(['errors'=> $validator->errors()], 422);
            }
        
        }


        if ($request->password != null) {
            $unhashed_password = $request->password;
            $password = Hash::make($request->password);
            $request->merge(['password' => $password]);
        }
        
        if($request->password == null){
            
            $user_social = User::where('social_id', '=', $request->social_id)->first();
            
            if ($user_social != null) {
                return $this->login($user_social);
            }

            $data['id'] = $request->id;
            $data['social_id'] = $request->social_id;
            $data['social_network'] = $request->social_network;

        } 

        $user = User::create($request->all());

        if ($request->password != null) {
            $data['id'] = $user->id;
            $data['email'] = $user->email;
            $data['password'] = $user->password;
            $data['unhashed_password'] = $unhashed_password;
        }

        return $this->login($data);
    }

    public function login()
    {

        $credentials = request(['email', 'password', 'unhashed_password',
             'login_type', 'social_id' ]);

        if ($credentials['login_type'] == 'email') {
            $email = $credentials['email'];
            $user = User::where('email', '=', $email)->first();
            $user_id = $user->id;
        }
        
        if ($credentials['login_type'] == 'social') {
            $social_id = $credentials['social_id'];
            $user = User::where('social_id', '=', $social_id)->first();
        }

        if ($credentials['login_type'] == 'email') {

            $data['email'] = $credentials['email'];
            $data['password'] = 12345678;
            
            if (! $token = auth()->attempt($data)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
        }
        
        if ($credentials['login_type'] == 'social') {

            $data['social_id'] = $credentials['social_id'];

            $user_social = User::where('social_id', '=', $data['social_id'])->first();
            
            if ($user_social == null) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $user_id = $user_social->id;
            
            if (! $token = auth()->tokenById($user->id)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return $this->respondWithTokenForSocial($token, $user_id);
            
        }
        
        return $this->respondWithToken($token, $user_id);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    
    protected function respondWithTokenForSocial($token, $user_id)
    {
        $this->saveMobileToken( $token, $user_id);

        $user = User::find($user_id);
        
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $user,
            'name' => $user->name,
            'id' => $user->id,
            'email' => $user->email,
            'token_id' => $token ? $token : null
        ]);
    }

    protected function respondWithToken($token, $user_id)
    {
        $this->saveMobileToken($token, $user_id);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
            'name' => auth()->user()->name,
            'id' => auth()->user()->id,
            'email' => auth()->user()->email,
            'token_id' => $token ? $token : null
        ]);
    }


    public function saveMobileToken( $token, $user_id){
        
        $user = User::find($user_id);
        $user->token_id = $token;
        $user->save();

        return true;
    }

}
