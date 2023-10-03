<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
// use App\Http\Requests\CategoryRequest;

class UsersController extends Controller
{
    public function show(){
        $data = User::paginate(5);

        return response()->json($data);
    }

    public function searchByKeyword($keyword){
        $data = User::where('fullname', 'like', '%'.$keyword.'%')->orWhere('username_account', 'like', '%'.$keyword.'%')->paginate(5);

        return response()->json($data);
    }

    public function store(RegisterRequest $request){
        $username = $request->user()->username_account;
        $role = $request->user()->role;
        if($role=="admin" && $username=="admin"){
            $greatestId = User::orderByDesc('_id')->first();
            if($greatestId){
                $id = $greatestId["_id"]+1;
            }
            else $id = 1;
            $role = (!$request["role"]) ? "user" : $request["role"];
            $image = (!$request["filename"]) ? "default.jpg" : $request["filename"];
            DB::table('users')->insert([
                "_id" => $id,
                "fullname" => $request["fullname"],
                "address" => $request["address"],
                "phonenumber" => $request["phone"],
                "email" => $request["email"],
                "avatar" => $image,
                "username_account" => $request["username"],
                "password_account" => Hash::make($request["password"]),
                "role" => $role,
            ]);
            return response()->json(['status'=> 200, 'message'=>'Thêm mới tài khoản thành công'], 200);
        }
        else return response()->json(['status'=> 404, 'message'=>'Bạn không có quyền này!'], 404);
    }

    public function setAdmin($id, Request $request){
        $role = $request->user()->role;
        $username = $request->user()->username_account;
        $userid = $request->user()->_id;
        if($role=="admin" && $username=="admin"){
            $data = User::where("_id", (int)$id)->first();
            if($data) {
                if($userid!=$id){
                    if(($data->role)=="admin"){
                        $data->update([
                            "role" => "user",
                        ]);
                    }
                    else{
                        $data->update([
                            "role" => "admin",
                        ]);
                    }
                    return response()->json(['status'=> 200, 'message'=>'Dữ liệu đã được cập nhật thành công'], 200);
                }
                else return response()->json(['status'=> 404, 'message'=>'Bạn không thể thay đổi vai trò của chính mình'], 404);
            }
            else return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại'], 404);
        }
        else return response()->json(['status'=> 404, 'message'=>'Bạn không có quyền này!'], 404);
    }

    public function delete($id, Request $request){
        $role = $request->user()->role;
        $username = $request->user()->username_account;
        $userid = $request->user()->_id;
        if($role=="admin" && $username=="admin"){
            $data = User::where("_id", (int)$id)->first();
            if($data) {
                if($userid!=$id){
                    $data->delete();
                    return response()->json(['status'=> 200, 'message'=>'Dữ liệu đã được xóa thành công'], 200);
                }
                else return response()->json(['status'=> 404, 'message'=>'Bạn không thể xóa chính mình'], 404);
            }
            else return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại'], 404);
        }
        else return response()->json(['status'=> 404, 'message'=>'Bạn không có quyền này!'], 404);
    }

    public function getUserFromToken(Request $request){
        return $request->user();
    }
}
