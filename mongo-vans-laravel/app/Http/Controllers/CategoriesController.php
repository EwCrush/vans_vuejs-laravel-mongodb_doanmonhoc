<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use DB;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\Auth;


class CategoriesController extends Controller
{
    public function show(){
        $data = Category::paginate(5);

        return response()->json($data);
    }

    public function showAll(){
        $data = Category::all();

        return response()->json($data);
    }

    public function showIdName(){
        $data = Category::raw((function($collection) {
            return $collection->aggregate([
                [
                    '$project' => [
                        '_id' => 0,
                        'value' => '$_id',
                        'label' => '$categoryname',
                    ]
                ],
            ]);
       }));

        return response()->json($data);
    }

    public function searchByKeyword($keyword){
        $data = Category::where('categoryname', 'like', '%'.$keyword.'%')->paginate(5);

        return response()->json($data);
    }

    public function searchByID($id){
        $data = Category::find($id);

        return response()->json($data);
    }

    public function store(CategoryRequest $request){
        $role = $request->user()->role;
        if($role=="admin"){
            $greatestId = Category::orderByDesc('_id')->first();
            if($greatestId){
                $id = $greatestId["_id"]+1;
            }
            else $id = 1;

            DB::table('categories')->insert([
                "_id" => $id,
                "categoryname" => $request["categoryname"],
            ]);
            return response()->json(['status'=> 200, 'message'=>'Dữ liệu đã được thêm thành công'], 200);
        }
        else return response()->json(['status'=> 404, 'message'=>'Bạn không có quyền này!'], 404);
    }

    public function edit(CategoryRequest $request, $id){
        $role = $request->user()->role;
        if($role=="admin"){
            Category::find($id)->update([
                "categoryname" => $request["categoryname"],
            ]);
            return response()->json(['status'=> 200, 'message'=>'Dữ liệu đã được cập nhật thành công'], 200);
        }
        else return response()->json(['status'=> 404, 'message'=>'Bạn không có quyền này!'], 404);
    }

    public function delete($id, Request $request){
        //$category = Category::firstWhere('categoryname', 'like', 'Eraaa');
        $role = $request->user()->role;
        if($role=="admin"){
            $category = Category::find($id);
            if($category){
                $category->delete();
                return response()->json(['status'=> 200, 'message'=>'Dữ liệu đã được xóa thành công'], 200);
            }
            else return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại'], 404);
        }
        else return response()->json(['status'=> 404, 'message'=>'Bạn không có quyền này!'], 404);
        //return response()->json(['id'=>$id]);
    }

    public function test(){
        $greatestId = Category::orderByDesc('_id')->first();
            if($greatestId){
                $id = $greatestId["_id"]+1;
            }
            else $id = 1;

        return $id;
    }
}
