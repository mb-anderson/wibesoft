<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class AuthController extends Controller
{
    public $successStatus = 200;

    /**
     *
     * @param [string] name
     * @param [string] email
     * @param [string] password
     * @return [string] message
     */
    public function register(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "email" => "required|string|email|unique:users",
            "password" => "required|string"
        ]);
        $user = new User([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password)
        ]);
        $user->save();

        $message["success"] = "User Created Successfully";
       
        return response()->json([
            "message" => $message
        ], 201);
    }

    /**
     *
     * @param [string] email
     * @param [string] password
     * @return [string] token
     * @return [string] token_type
     * @return [string] expires_at
     * @return [string] success
     */
    public function login(Request $request)
    {

        $request->validate([
            "email" => "required|string|email",
            "password" => "required|string"
        ]);

        $credentials = request(["email", "password"]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $message["token"] = $user->createToken("WibeSoft")->accessToken;
            $message["token_type"] = "Bearer";
            $message["exipires_at"] = Carbon::parse(Carbon::now()->addWeeks(1))->toDateTimeString();
            $message["success"] = "Login Successfull";

            return response()->json(["message" => $message], $this->successStatus);
        } else {
            return response()->json(["error" => "Unauthorised"], 401);
        }
    }
}
