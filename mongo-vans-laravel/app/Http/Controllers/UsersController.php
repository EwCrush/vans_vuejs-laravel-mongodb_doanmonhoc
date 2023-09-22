<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;
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
}
