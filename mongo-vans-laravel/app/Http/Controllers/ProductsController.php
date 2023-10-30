<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Bill;
use App\Models\Comment;
use App\Models\Notification;
use DB;
use Illuminate\Pagination\Paginator;
use App\Http\Requests\SizeRequest;
use App\Http\Requests\ImageRequest;
use App\Http\Requests\ProductRequest;

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
                        'status' => 1,
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
                        'status' => 1,
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

    public function deleteImage($id, ImageRequest $request){
        $role = $request->user()->role;
        if($role=="admin"){
            $product = Product::where("_id", (int)$id)->first();
            if($product){
                $image = Product::where("_id", (int)$id)->where('images', 'elemMatch', ['filename'=>$request["filename"]])->first();
                if($image){
                    Product::where("_id", (int)$id)
                    ->pull('images', array( 'filename' => $request["filename"]));
                    return response()->json(['status'=> 200, 'message'=>'Dữ liệu đã được thêm thành công'], 200);
                }
                else{
                    return response()->json(['status'=> 404, 'message'=>'Hình ảnh không tồn tại!'], 404);
                }
                Product::where("_id", (int)$id)
                ->pull('images', array( 'filename' => $request["filename"]));
                return response()->json(['status'=> 200, 'message'=>'Hình ảnh đã được xóa thành công'], 200);
            }
            else{
                return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại!'], 404);
            }
        }
        else return response()->json(['status'=> 404, 'message'=>'Bạn không có quyền này!'], 404);
    }

    public function storeProduct(ProductRequest $request){
        $role = $request->user()->role;
        if($role=="admin"){
            $greatestId = Product::orderByDesc('_id')->first();
            if($greatestId){
                $id = $greatestId["_id"]+1;
            }
            $image = !$request["filename"] ? "defaultimg.png" : $request["filename"];
            DB::table('products')->insert([
                "_id" => $id,
                "productname" => $request["productname"],
                "originalprice" => (int)$request["originalprice"],
                "sellingprice" => (int)$request["sellingprice"],
                "thumbnail" => $image,
                "images" => [],
                "category" => $request["category_id"],
                "subcategory" => $request["subcategory_id"],
                "sizes" => [],
                "status" => "public",
            ]);
            return response()->json(['status'=> 200, 'message'=>'Dữ liệu đã được thêm thành công'], 200);

        }
        else return response()->json(['status'=> 404, 'message'=>'Bạn không có quyền này!'], 404);
    }

    public function editProduct($id, ProductRequest $request){
        $role = $request->user()->role;
        if($role=="admin"){
            $data = Product::where('_id', (int)$id)->first();
            if($data){
                $img_request = $request["filename"];
                $old_img = $data->thumbnail;
                if($img_request){
                    $data->update([
                        "productname" => $request["productname"],
                        "originalprice" => $request["originalprice"],
                        "sellingprice" => $request["sellingprice"],
                        "category" => $request["category_id"],
                        "subcategory" => $request["subcategory_id"],
                        "thumbnail" => $img_request,
                    ]);
                    return response()->json(['status'=> 200,
                                            'message'=>'Dữ liệu đã được cập nhật thành công',
                                            'filename' => $old_img], 200);
                }
                else{
                    $data->update([
                        "productname" => $request["productname"],
                        "originalprice" => $request["originalprice"],
                        "sellingprice" => $request["sellingprice"],
                        "category" => $request["category_id"],
                        "subcategory" => $request["subcategory_id"],
                    ]);
                    return response()->json(['status'=> 200,
                                            'message'=>'Dữ liệu đã được cập nhật thành công',], 200);
                }
            }
            else{
                return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại!'], 404);
            }
        }
        else return response()->json(['status'=> 404, 'message'=>'Bạn không có quyền này!'], 404);
    }

    public function deleteProduct($id, Request $request){
        $role = $request->user()->role;
        if($role=="admin"){
            $product = Product::where("_id", (int)$id)->first();
            if($product){
                $cart = Bill::where('items', 'elemMatch', ['productid'=>(int)$id])->first();
                if(!$cart){
                    $img = $product->thumbnail;
                    //xoa comments trong san pham
                    $comments = Comment::where('product', (int)$id)->get();
                    foreach ($comments as $comment) {
                        //xoa thong bao lien quan den comment
                        $notifies = Notification::where("comment", $comment->_id)->get();
                        foreach ($notifies as $notify) {
                            $notify->delete();
                        }
                        $comment->delete();
                    }
                    $product->delete();
                    return response()->json(['status'=> 200,
                                            'message'=>'Dữ liệu đã được xóa thành công',
                                            'filename'=>$img], 200);
                }
                else{
                    return response()->json(['status'=> 404, 'message'=>'Không thể xóa sản phẩm đã được bán đi, bạn có thể ẩn chúng nếu muốn ngừng bán'], 404);
                }
            }
            else return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại'], 404);
        }
        else return response()->json(['status'=> 404, 'message'=>'Bạn không có quyền này!'], 404);
    }

    public function showByFilter(Request $request){
        $data = Product::when($request->filled('id'), function($query) use ($request) {
                $query->where("category", (int)$request->query('id'))
                ->orWhere("subcategory", (int)$request->query('id'));
            })
            ->when($request->query('saleoff') == 'true', function ($query) {
                $query->whereRaw([
                    '$expr' => [
                        '$gt' => [
                            ['$toDouble' => '$originalprice'],
                            ['$toDouble' => '$sellingprice']
                        ]
                    ]
                ]);
            })
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $query->where("productname", "like", '%' . $request->query('keyword') . '%');
            })
            ->when($request->filled('order'), function ($query) use ($request) {
                $order = $request->query('order');
                switch ($order) {
                    case 'az':
                        return $query->orderBy('productname', 'ASC');
                    case 'za':
                        return $query->orderBy('productname', 'DESC');
                    case 'increase':
                        return $query->orderBy('sellingprice', 'ASC');
                    case 'decrease':
                        return $query->orderBy('sellingprice', 'DESC');
                }
            })
            ->where('status', 'public')
            ->paginate(12)->withQueryString();
        return response()->json($data);
    }

    public function searchByCategory($id){
        $product = Product::where("_id", (int)$id)->first();
            if($product){
                $category = $product->category;
                $subcategory = $product->subcategory;
                $data = Product::where("category", $category)
                ->orWhere("subcategory", $category)
                ->orWhere("category", $subcategory)
                ->orWhere("subcategory", $subcategory)
                ->offset(0)->limit(10)->get();
                return response()->json($data);
            }
            else return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại'], 404);

        // return response()->json($data);

    }

    public function searchByClassic(){
        $product = Product::where('category', 7)->orWhere('subcategory', 7)->limit(10)->get();
        return response()->json($product, 200);
    }

    public function editProductStatus($id, Request $request){
        $role = $request->user()->role;
        if($role=="admin"){
            $product = Product::where("_id", (int)$id)->first();
            if($product){
                if($product->status=='public'){
                    $product->update(['status' => 'private']);
                }
                else {
                    $product->update(['status' => 'public']);
                }
                return response()->json(['status'=> 200, 'message'=>'Dữ liệu đã được cập nhật thành công'], 200);
            }
            else return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại'], 404);
        }
        else return response()->json(['status'=> 404, 'message'=>'Bạn không có quyền này!'], 404);
    }
}
