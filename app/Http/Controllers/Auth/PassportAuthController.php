<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\PassportLoginRequest;
use App\Http\Requests\PassportRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PassportAuthController extends ApiController
{
    /**
     * Registration Req
     */
    public function register(PassportRegisterRequest $request)
    {
        //Register always will be as a student 

        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'Teacher'
            ]);

            $token = $user->createToken('Laravel8PassportAuth')->accessToken;

            $data = [
                'access_token' => $token,
                'user' => $user->first_name." ".$user->last_name
            ];

            return $this->successResponse($data, 200);
        } catch (\Exception $exception) {
            $errorException = $exception->getMessage();
            return $this->errorResponse($errorException, 400);
        }
    }

    /**
     * Login Req
     */
    public function login(PassportLoginRequest $request)
    {

        try {
            if (Auth::attempt($request->only('email', 'password'))) {
                $user =  Auth::user();
                $token = $user->createToken('Laravel8PassportAuth')->accessToken;
                $data = [
                    'access_token' => $token,
                    'user' => $user->first_name." ".$user->last_name
                ];
                return $this->successResponse($data, 200);
            }
        } catch (\Exception $exception) {
            $errorException = $exception->getMessage();
            return $this->errorResponse($errorException, 400);
        }
        return $this->errorResponse('Unauthorised', 401);
    }

    /**
     * User info Req
     */

    public function userInfo()
    {
        $user = Auth::user();
        return $this->successResponse($user, 200);
    }

    /**
     * Logout Req
     */
    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return $this->successResponse($response, 200);
    }
}
