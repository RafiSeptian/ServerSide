<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\Response;
use App\User;
use Auth;
use Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('APIToken')->only(['logout']);
        $this->token = request('token');
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'unique:users,username|regex:/^[a-zA-Z0-9._]+$/',
            'first_name' => 'min:2|max:20|regex:/^[a-zA-Z]+$/',
            'last_name' => 'min:2|max:20|regex:/^[a-zA-Z]+$/',
            'password' => 'min:5|max:12'
        ]);

        if($validator->fails()){
            return Response::notvalid(['message' => 'Invalid Field']);
        }

        $data = $request->all();

        $data['password'] = bcrypt($request->password);

        $user = User::create($data);
        $token = $user->token()->create([
            'user_id' => $user->id,
            'token' => bcrypt($user->id)
        ]);

        return Response::success(['token' => $token->token]);
    }

    public function login(Request $request){
        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if(Auth::attempt($credentials)){

            $token = Auth::user()->token;

            $newToken = bcrypt(Auth::user()->id);

            $token->update([
                'token' => $newToken
            ]);

            return Response::success(['token' => $token->token]);
        }

        return Response::invalid(['message' => 'Invalid Login']);
    }

    public function logout(){
        $check = \App\LoginToken::whereToken($this->token)->first();

        if($check){
            $check->update([
                'token' => null
            ]);

            return Response::success(['message' => 'Logout Success']);
        }

        return Response::invalid(['message' => 'Unauthorized User']);
    }
}
