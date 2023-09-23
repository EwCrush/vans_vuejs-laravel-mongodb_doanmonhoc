<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use DB;
use Illuminate\Pagination\Paginator;
use App\Http\Requests\SizeRequest;
use App\Http\Requests\ImageRequest;

class ProductsController extends Controller
{
    public function show(){
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 5;
        $total = Product::count();

        $data = Product::raw((function($collection) use ($page, $perPage) {
            return $collection->aggregate([
              [
                '$lookup' => [
                  'from' => 'categories',
                  'localField' => 'category',
                  'foreignField'=> '_id',
                  'as' => 'category'
                ]
                ],
                [
                    '$unwind' => '$category'
                ],
                [
                    '$lookup' => [
                      'from' => 'categories',
                      'localField' => 'subcategory',
                      'foreignField'=> '_id',
                      'as' => 'subcategory'
                    ]
                ],
                [
                    '$unwind' => '$subcategory'
                ],
                [
                    '$project' => [
                        '_id' => 1,
                        'productname' => 1,
                        'originalprice' => 1,
                        'sellingprice' => 1,
                        'thumbnail' => 1,
                        'images' => 1,
                        'sizes' => 1,
                        'category' => '$category.categoryname',
                        'subcategory' => '$subcategory.categoryname',
                    ]
                ],
                ['$skip' => ($page - 1) * $perPage],
                ['$limit' => $perPage],
            ]);
       }));

        return new \Illuminate\Pagination\LengthAwarePaginator($data, $total, $perPage, $page, [
            'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
        ]);
    }

    public function showAll(){
        $data = Product::all();

        return response()->json($data);
    }

    public function searchByKeyword($keyword){
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 5;
        $total = Product::where('productname', 'regexp', '/'.$keyword.'/')->count();

        $data = Product::raw((function($collection) use ($page, $perPage, $keyword) {
            return $collection->aggregate([
              [
                '$lookup' => [
                  'from' => 'categories',
                  'localField' => 'category',
                  'foreignField'=> '_id',
                  'as' => 'category'
                ]
                ],
                [
                    '$unwind' => '$category'
                ],
                [
                    '$lookup' => [
                      'from' => 'categories',
                      'localField' => 'subcategory',
                      'foreignField'=> '_id',
                      'as' => 'subcategory'
                    ]
                ],
                [
                    '$unwind' => '$subcategory'
                ],

                ['$match' => ['productname' => ['$regex' => '^.*'.$keyword.'.*$']]],

                [
                    '$project' => [
                        '_id' => 1,
                        'productname' => 1,
                        'originalprice' => 1,
                        'sellingprice' => 1,
                        'thumbnail' => 1,
                        'images' => 1,
                        'sizes' => 1,
                        'category' => '$category.categoryname',
                        'subcategory' => '$subcategory.categoryname',
                    ]
                ],
                ['$skip' => ($page - 1) * $perPage],
                ['$limit' => $perPage],
            ]);
       }));

        return new \Illuminate\Pagination\LengthAwarePaginator($data, $total, $perPage, $page, [
            'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
        ]);
    }

    public function searchByID($id){
        $data = Product::where("_id", (int)$id)->first();
        if($data){
            return response()->json($data);
        }
        else{
            return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại!'], 404);
        }
    }

    public function showSizes($id, Request $request){
        $field = Product::where('_id', (int)$id)->first();
        if($field){
            $size = $request->query('size');
            if(!$size){
                $data = Product::raw((function($collection) use($id) {
                    return $collection->aggregate([
                        [
                            '$match' => [
                                    '_id' => [ '$eq' => (int)$id]
                            ],
                        ],
                        [
                            '$unwind' => '$sizes'
                        ],
                        [
                            '$project' => [
                                '_id' => 0,
                                'size' => '$sizes.size',
                                'quantity' => '$sizes.quantity',
                            ]
                        ],
                    ]);
                }));
            }
            else{
                $arrayitem = Product::where("_id", (int)$id)->where('sizes', 'elemMatch', ['size'=>$size])->first();
                if($arrayitem){
                    $data = Product::raw((function($collection) use($id, $size) {
                        return $collection->aggregate([
                            [
                                '$match' => [
                                        '_id' => [ '$eq' => (int)$id],
                                        'sizes.size' => $size
                                ],
                            ],
                            [
                                '$unwind' => '$sizes'
                            ],
                            [
                                '$match' => [
                                        '_id' => [ '$eq' => (int)$id],
                                        'sizes.size' => $size
                                ],
                            ],
                            [
                                '$project' => [
                                    '_id' => 0,
                                    'size' => '$sizes.size',
                                    'quantity' => '$sizes.quantity',
                                ]
                            ],
                        ]);
                    }));
                }
                else{
                    return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại!'], 404);
                }
            }
            return response()->json($data);
        }
        else{
            return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại!'], 404);
        }
    }

    public function showImages($id){
        $isExist = Product::where('_id', (int)$id)->first();
        if($isExist){
            $data = Product::raw((function($collection) use($id) {
                return $collection->aggregate([
                    [
                        '$match' => [
                                '_id' => [ '$eq' => (int)$id]
                           ],
                    ],
                    [
                        '$unwind' => '$images'
                    ],
                    [
                        '$project' => [
                            '_id' => 0,
                            'filename' => '$images.filename',
                        ]
                    ],
                ]);
           }));
            return response()->json($data);
        }
        else{
            return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại!'], 404);
        }
    }

    public function storeSize($id, SizeRequest $request){
        $role = $request->user()->role;
        if($role=="admin"){
            $product = Product::where("_id", (int)$id)->first();
            if($product){
                $size = Product::where("_id", (int)$id)->where('sizes', 'elemMatch', ['size'=>$request->size])->first();
                if($size){
                    return response()->json(['status'=> 404, 'message'=>'Dữ liệu đã tồn tại!'], 404);
                }
                else{
                    Product::where("_id", (int)$id)
                    ->push('sizes', array( 'size' => $request["size"], 'quantity' => (int)$request["quantity"] ));
                    return response()->json(['status'=> 200, 'message'=>'Dữ liệu đã được thêm thành công'], 200);
                }
            }
            else{
                return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại!'], 404);
            }
        }
        else return response()->json(['status'=> 404, 'message'=>'Bạn không có quyền này!'], 404);
    }

    public function deleteSize($id, Request $request){
        $role = $request->user()->role;
        if($role=="admin"){
            $product = Product::where("_id", (int)$id)->first();
            if($product){
                $size = Product::where("_id", (int)$id)->where('sizes', 'elemMatch', ['size'=>$request->query('size')])->first();
                if(!$size){
                    return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại!'], 404);
                }
                else{
                    Product::where("_id", (int)$id)
                    ->pull('sizes', array( 'size' => $request->query('size')));
                    return response()->json(['status'=> 200, 'message'=>'Dữ liệu đã được xóa thành công'], 200);
                }
            }
            else{
                return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại!'], 404);
            }
        }
        else return response()->json(['status'=> 404, 'message'=>'Bạn không có quyền này!'], 404);
    }

    public function editSize($id, SizeRequest $request){
        $role = $request->user()->role;
        if($role=="admin"){
            $size = $request->query('size');
            $product = Product::where("_id", (int)$id)->first();
            if($product){
                $field = Product::where("_id", (int)$id)->where('sizes', 'elemMatch', ['size'=>$size])->first();
                if($field){
                    $collection = Product::raw((function($collection) use($id, $size) {
                        return $collection->aggregate([
                            [
                                '$match' => [
                                        '_id' => [ '$eq' => (int)$id],
                                        'sizes.size' => $size
                                ],
                            ],
                            [
                                '$unwind' => '$sizes'
                            ],
                            [
                                '$match' => [
                                        '_id' => [ '$eq' => (int)$id],
                                        'sizes.size' => $size
                                ],
                            ],
                            [
                                '$project' => [
                                    '_id' => 0,
                                    'size' => '$sizes.size',
                                    'quantity' => '$sizes.quantity',
                                ]
                            ],
                        ]);
                    }));
                    if($collection[0]['size']==$request['size']){
                        //xoa
                        Product::where("_id", (int)$id)
                        ->pull('sizes', array( 'size' => $request->query('size')));
                        //them
                        Product::where("_id", (int)$id)
                        ->push('sizes', array( 'size' => $request["size"], 'quantity' => (int)$request["quantity"] ));
                        return response()->json(['status'=> 200, 'message'=>'Dữ liệu đã được cập nhật thành công'], 200);
                    }
                    else{
                        $isExist = Product::where("_id", (int)$id)
                                    ->where('sizes', 'elemMatch', ['size'=>$request['size']])->first();
                        if($isExist){
                            return response()->json(['status'=> 404, 'message'=>'Size này đã tồn tại!'], 404);
                        }
                        else{
                            //xoa
                            Product::where("_id", (int)$id)
                            ->pull('sizes', array( 'size' => $request->query('size')));
                            //them
                            Product::where("_id", (int)$id)
                            ->push('sizes', array( 'size' => $request["size"], 'quantity' => (int)$request["quantity"] ));
                            return response()->json(['status'=> 200, 'message'=>'Dữ liệu đã được cập nhật thành công'], 200);
                        }
                    }
                }
                else{
                    return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại!'], 404);
                }
            }
            else{
                return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại!'], 404);
            }
        }
        else return response()->json(['status'=> 404, 'message'=>'Bạn không có quyền này!'], 404);
    }

    public function storeImage($id, ImageRequest $request){
        $role = $request->user()->role;
        if($role=="admin"){
            $product = Product::where("_id", (int)$id)->first();
            if($product){
                Product::where("_id", (int)$id)
                ->push('images', array( 'filename' => $request["filename"]));
                return response()->json(['status'=> 200, 'message'=>'Dữ liệu đã được thêm thành công'], 200);
            }
            else{
                return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại!'], 404);
            }
        }
        else return response()->json(['status'=> 404, 'message'=>'Bạn không có quyền này!'], 404);
    }
}
