<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try{
            DB::beginTransaction();

            Log::info($request);
            Log::info($request->headers->all());

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);
            if($validator->fails()){
                error_log("validator failed");
                error_log($validator->errors());
                throw new ValidationException($validator);
            }
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'type' => 'user',
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;
    
            DB::commit();            
        } catch (\Exception $e){
            DB::rollback();

            if ($e instanceof ValidationException) {
                return response()->json([
                    'status' => 422,
                    'message' => $e->errors()
                ], 422);
            }

            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'token' => $token,
            'userType' => $user->type
        ],200);
    }

    public function login(Request $request)
    {
        try{
            Log::info($request);

            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
                'password' => 'required|string',
            ]);
            if($validator->fails()){
                throw new ValidationException($validator);
            }
    
            $user = User::where('email', $request->email)->first();
            if(!$user || !Hash::check($request->password, $user->password)){
                return response()->json([
                    'status' => 401,
                    'message' => 'Email and/or password is incorrect'
                ], 401);
            }
    
            $token = $user->createToken('auth_token')->plainTextToken;
                        
        } catch (\Exception $e){
            if ($e instanceof ValidationException) {
                return response()->json([
                    'status' => 422,
                    'message' => $e->errors()
                ], 422);
            }
            
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'token' => $token,
            'name' => $user->name,
            'userType' => $user->type
        ],200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => 200,
            'message' => 'You have been successfully logged out'
        ], 200);
    }
}
