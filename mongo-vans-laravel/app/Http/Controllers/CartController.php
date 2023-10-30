<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Product;
use DB;
use App\Http\Requests\CartRequest;
use App\Http\Requests\ConsigneeRequest;
use GoogleMaps\GoogleMaps;

class CartController extends Controller
{
    public function addToCart(CartRequest $request){
        $product = $request->product;
        $size = $request->size;
        $quantity = $request->quantity;
        $checksize = Product::where("_id", $product)
        ->where('sizes', 'elemMatch', ['size'=>$size, 'quantity' => ['$gte' => $quantity]])->first();
        if($checksize){
            //lay ra thong tin product
            $productinfo = Product::where('_id', $product)->first();
            //kiem tra da co cart chua?
            $checkcart = Bill::where('user', $request->user()->_id)
                        ->where('status', 'cart')->orderBy('created_at', 'DESC')->first();
            if(!$checkcart){
                //tao cart moi
                Bill::create([
                    "user" => $request->user()->_id,
                    "fullname_consignee" => $request->user()->fullname,
                    "numberphone_consignee" => $request->user()->phonenumber,
                    "address_consignee" => $request->user()->address,
                    "status" => 'cart',
                    "shippingcost" => 0,
                    "total" => 0,
                    "items" => []
                ]);
            }
            $cart = Bill::where('user', $request->user()->_id)
                        ->where('status', 'cart')->orderBy('created_at', 'DESC')->first();
            $item = Bill::where("_id", $cart->_id)->where('items', 'elemMatch', ['productid'=>$product, 'size' => $size])->first();
            if($item){
                $collection = Bill::raw(function($collection) use ($request, $size, $product) {
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'user' => $request->user()->_id,
                                'status' => 'cart',
                                'items.productid' => $product,
                                'items.size' => $size
                            ],
                        ],
                        [
                            '$unwind' => '$items'
                        ],
                        [
                            '$match' => [
                                'user' => $request->user()->_id,
                                'status' => 'cart',
                                'items.productid' => $product,
                                'items.size' => $size
                            ],
                        ],
                        [
                            '$project' => [
                                '_id' => 0,
                                'size' => '$items.size',
                                'quantity' => '$items.quantity',
                            ]
                        ],
                        [
                            '$limit' => 1
                        ],
                    ]);
                });

                $oldItem = $collection[0];
                //thay doi so luong, gia tien
                $cart->pull('items', array( 'productid' => $product, 'size' => $size));
                $cart->push('items', array('productid' => $product,
                                            'productname' => $productinfo->productname,
                                            'image' => $productinfo->thumbnail,
                                            'size' => $size,
                                            'price' => $productinfo->sellingprice,
                                            'quantity' => $quantity+$oldItem->quantity,
                                            'amount' => $productinfo->sellingprice*($quantity+$oldItem->quantity)));
            }
            else{
                //them san pham
                $cart->push('items', array('productid' => $product,
                                            'productname' => $productinfo->productname,
                                            'image' => $productinfo->thumbnail,
                                            'size' => $size,
                                            'price' => $productinfo->sellingprice,
                                            'quantity' => $quantity,
                                            'amount' => $productinfo->sellingprice*$quantity));
            }
            //update so luong size san pham
            $productwithsize = Product::raw((function($collection) use($product, $size) {
                return $collection->aggregate([
                    [
                        '$match' => [
                                '_id' => [ '$eq' => (int)$product],
                                'sizes.size' => $size
                        ],
                    ],
                    [
                        '$unwind' => '$sizes'
                    ],
                    [
                        '$match' => [
                                '_id' => [ '$eq' => (int)$product],
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

            Product::where("_id", $product)
            ->pull('sizes', array( 'size' => $size));
            Product::where("_id", $product)
            ->push('sizes', array( 'size' => $size, 'quantity' => ($productwithsize[0]['quantity'] - $quantity)));

            //update tong tien
            $cartForUpdateTotal = Bill::raw(function($collection) use ($request) {
                return $collection->aggregate([
                    [
                        '$match' => [
                            'user' => $request->user()->_id,
                            'status' => 'cart',
                        ],
                    ],
                    [
                        '$sort' => ['created_at' => -1]
                    ],
                    [
                        '$limit' => 1
                    ],
                ]);
            });

            $totalAmount = 0;
            $totalItem = 0;
            foreach ($cartForUpdateTotal[0]->items as $item) {
                $totalAmount += $item['amount'];
                $totalItem += 1;
            }
            $cartForUpdateTotal[0]->update(['shippingcost' => $totalItem*15000,'total' => $totalAmount+($totalItem*15000)]);
            return response()->json(['status'=> 200, 'message'=>'Đã thêm vào giỏ hàng'], 200);
        }
        else{
            return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại!'], 404);
        }
    }

    public function showCart(Request $request){
        //kiem tra da co cart chua?
        $checkcart = Bill::where('user', $request->user()->_id)
                    ->where('status', 'cart')->orderBy('created_at', 'DESC')->first();
        if(!$checkcart){
            //tao cart moi
            Bill::create([
                "user" => $request->user()->_id,
                "fullname_consignee" => $request->user()->fullname,
                "numberphone_consignee" => $request->user()->phonenumber,
                "address_consignee" => $request->user()->address,
                "status" => 'cart',
                "shippingcost" => 0,
                "total" => 0,
                "items" => []
            ]);
        }
        $cart = Bill::where('user', $request->user()->_id)
                    ->where('status', 'cart')->orderBy('created_at', 'DESC')
                    ->orderBy('items.size', 'asc')
                    ->orderBy('items.productid', 'asc')
                    ->first();
        return response()->json($cart, 200);
    }

    public function deleteFromCart(Request $request){
        $user = $request->user()->_id;

        $size = $request->query('size');
        $product = (int)$request->query('product');

        $cart = Bill::where('user', $request->user()->_id)
                        ->where('status', 'cart')->orderBy('created_at', 'DESC')->first();
        $item = Bill::where("_id", $cart->_id)->where('items', 'elemMatch', ['productid'=>$product, 'size' => $size])->first();

        if($item){
            $collection = Bill::raw(function($collection) use ($user, $size, $product) {
                return $collection->aggregate([
                    [
                        '$match' => [
                            'user' => $user,
                            'status' => 'cart',
                            'items.productid' => $product,
                            'items.size' => $size
                        ],
                    ],
                    [
                        '$unwind' => '$items'
                    ],
                    [
                        '$match' => [
                            'user' => $user,
                            'status' => 'cart',
                            'items.productid' => $product,
                            'items.size' => $size
                        ],
                    ],
                    [
                        '$project' => [
                            '_id' => 0,
                            'size' => '$items.size',
                            'quantity' => '$items.quantity',
                        ]
                    ],
                    [
                        '$limit' => 1
                    ],
                ]);
            });
            //xoa item khoi gio hang
            $cart->pull('items', array( 'productid' => $product, 'size' => $size));
            //cap nhat lai tong tien cua gio hang
            $cartForUpdateTotal = Bill::raw(function($collection) use ($user) {
                return $collection->aggregate([
                    [
                        '$match' => [
                            'user' => $user,
                            'status' => 'cart',
                        ],
                    ],
                    [
                        '$sort' => ['created_at' => -1]
                    ],
                    [
                        '$limit' => 1
                    ],
                ]);
            });

            $totalAmount = 0;
            $totalItem = 0;
            foreach ($cartForUpdateTotal[0]->items as $item) {
                $totalAmount += $item['amount'];
                $totalItem += 1;
            }
            $cartForUpdateTotal[0]->update(['shippingcost' => $totalItem*15000,'total' => $totalAmount+($totalItem*15000)]);
            return response()->json(['status'=> 200, 'message'=>'Đã xóa khỏi giỏ hàng'], 200);
        }
        else return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại!'], 404);
    }

    public function editConsignee(ConsigneeRequest $request){
        $fullname = $request->fullnameConsignee;
        $phone = $request->phoneConsignee;
        $address = $request->addressConsignee;
        $user = $request->user()->_id;
        $cart = Bill::where('user', $request->user()->_id)
                    ->where('status', 'cart')->orderBy('created_at', 'DESC')->first();
        $cart->update([
            'fullname_consignee' => $fullname,
            'numberphone_consignee' => $phone,
            'address_consignee' => $address
        ]);
        return response()->json(['status'=> 200, 'message'=>'Đã thay đổi thông tin đặt hàng'], 200);
    }

    public function editQuantity(CartRequest $request){
        $product = $request->product;
        $size = $request->size;
        $quantity = $request->quantity;
        $newQuantitySize = 0;
        $newCheckQuantitySize = 0;
        $checksize = Product::where("_id", $product)->where('sizes', 'elemMatch', ['size'=>$size])->first();
        if($checksize){
            //lay ra thong tin product
            $productinfo = Product::where('_id', $product)->first();

            //kiem tra da co cart chua?
            $checkcart = Bill::where('user', $request->user()->_id)
                        ->where('status', 'cart')->orderBy('created_at', 'DESC')->first();
            if(!$checkcart){
                //tao cart moi
                Bill::create([
                    "user" => $request->user()->_id,
                    "fullname_consignee" => $request->user()->fullname,
                    "numberphone_consignee" => $request->user()->phonenumber,
                    "address_consignee" => $request->user()->address,
                    "status" => 'cart',
                    "shippingcost" => 0,
                    "total" => 0,
                    "items" => []
                ]);
            }
            $cart = Bill::where('user', $request->user()->_id)
                        ->where('status', 'cart')->orderBy('created_at', 'DESC')->first();
            $item = Bill::where("_id", $cart->_id)->where('items', 'elemMatch', ['productid'=>$product, 'size' => $size])->first();
            if($item){
                $collection = Bill::raw(function($collection) use ($request, $size, $product) {
                    return $collection->aggregate([
                        [
                            '$match' => [
                                'user' => $request->user()->_id,
                                'status' => 'cart',
                                'items.productid' => $product,
                                'items.size' => $size
                            ],
                        ],
                        [
                            '$unwind' => '$items'
                        ],
                        [
                            '$match' => [
                                'user' => $request->user()->_id,
                                'status' => 'cart',
                                'items.productid' => $product,
                                'items.size' => $size
                            ],
                        ],
                        [
                            '$project' => [
                                '_id' => 0,
                                'size' => '$items.size',
                                'quantity' => '$items.quantity',
                            ]
                        ],
                        [
                            '$limit' => 1
                        ],
                    ]);
                });

                $oldItem = $collection[0];
                //kiem tra tang hay giam so luong
                if($oldItem->quantity < $quantity){
                    $newQuantitySize = - abs($oldItem->quantity - $quantity);
                    $newCheckQuantitySize = abs($oldItem->quantity - $quantity);
                }
                else{
                    $newQuantitySize = abs($oldItem->quantity - $quantity);
                    $newCheckQuantitySize = 0;
                }
                $checksizewithQuantity = Product::where("_id", $product)
                ->where('sizes', 'elemMatch', ['size'=>$size, 'quantity' => ['$gte' => $newCheckQuantitySize]])->first();
                if($checksizewithQuantity){
                    //thay doi so luong, gia tien
                    $cart->pull('items', array( 'productid' => $product, 'size' => $size));
                    $cart->push('items', array('productid' => $product,
                                                'productname' => $productinfo->productname,
                                                'image' => $productinfo->thumbnail,
                                                'size' => $size,
                                                'price' => $productinfo->sellingprice,
                                                'quantity' => $quantity,
                                                'amount' => $productinfo->sellingprice*$quantity));
                    //update so luong size san pham
                    $productwithsize = Product::raw((function($collection) use($product, $size) {
                        return $collection->aggregate([
                            [
                                '$match' => [
                                        '_id' => [ '$eq' => (int)$product],
                                        'sizes.size' => $size
                                ],
                            ],
                            [
                                '$unwind' => '$sizes'
                            ],
                            [
                                '$match' => [
                                        '_id' => [ '$eq' => (int)$product],
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

                    Product::where("_id", $product)
                    ->pull('sizes', array( 'size' => $size));
                    Product::where("_id", $product)
                    ->push('sizes', array( 'size' => $size, 'quantity' => ($productwithsize[0]['quantity'] + $newQuantitySize)));

                    //update tong tien
                    $cartForUpdateTotal = Bill::raw(function($collection) use ($request) {
                        return $collection->aggregate([
                            [
                                '$match' => [
                                    'user' => $request->user()->_id,
                                    'status' => 'cart',
                                ],
                            ],
                            [
                                '$sort' => ['created_at' => -1]
                            ],
                            [
                                '$limit' => 1
                            ],
                        ]);
                    });

                    $totalAmount = 0;
                    $totalItem = 0;
                    foreach ($cartForUpdateTotal[0]->items as $item) {
                        $totalAmount += $item['amount'];
                        $totalItem += 1;
                    }
                    $cartForUpdateTotal[0]->update(['shippingcost' => $totalItem*15000,'total' => $totalAmount+($totalItem*15000)]);
                    return response()->json(['status'=> 200, 'message'=>'Đã cập nhật giỏ hàng'], 200);
                }
                else {
                    return response()->json(['status'=> 404, 'message'=>'Sản phẩm đã hết hàng!'], 404);
                }
            }
            else {
                return response()->json(['status'=> 404, 'message'=>'Sản phẩm không tồn tại trong giỏ hàng!'], 404);
            }
        }
        else{
            return response()->json(['status'=> 404, 'message'=>'Sản phẩm không tồn tại!'], 404);
        }
    }

    public function order(Request $request){
        $cart = Bill::where('user', $request->user()->_id)
                        ->where('status', 'cart')->orderBy('created_at', 'DESC')->first();
        if($cart->address_consignee=="" || $cart->numberphone_consignee==""){
            return response()->json(['status'=> 422, 'message'=>'Vui lòng nhập số điện thoại và địa chỉ giao hàng!'], 422);
        }
        else {
            if(count($cart->items)==0){
                return response()->json(['status'=> 422, 'message'=>'Vui lòng mua gì đó!'], 422);
            }
            else {
                $cart->update([
                    'status' => 'confirmation pending'
                ]);
                return response()->json(['status'=> 200, 'message'=>'Đặt hàng thành công!'], 200);
            }
        }
    }
}
