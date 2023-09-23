<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request){
        $user = User::where('username_account', $request->username)->first();
        if(!$user){
            return response()->json(
                [
                'message' => 'Thông tin đăng nhập không tồn tài!',
            ],
            404
        );
        }
        else{
            if(Hash::check($request->password, $user->password_account)){
                $token = $user->createToken('authToken')->plainTextToken;
                return response()->json([
                    'access_token' => $token,
                    'type_token' => 'Bearer',
                    'role' => $user->role,
                ], 200);
            }
            else{
                return response()->json(
                    [
                    'message' => 'Sai mật khẩu!',
                ],
                404
                );
            }
        }
    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Đã đăng xuất!',
        ], 200);
    }

    public function register(RegisterRequest $request){
        $greatestId = User::orderByDesc('_id')->first();
        if($greatestId){
            $id = $greatestId["_id"]+1;
        }
        else $id = 1;
        DB::table('users')->insert([
            "_id" => $id,
            "fullname" => $request["fullname"],
            "address" => "",
            "numberphone" => $request["phone"],
            "email" => $request["email"],
            "avatar" => "default.jpg",
            "username_account" => $request["username"],
            "password_account" => Hash::make($request["password"]),
            "role" => 'user',
        ]);
        return response()->json(['status'=> 200, 'message'=>'Đăng ký tài khoản thành công'], 200);
        //return $id;
    }

    // public function userByToken(){
    //     $request->user()->role;
    // }

    public function loginGoogle(){
        
    }
}
