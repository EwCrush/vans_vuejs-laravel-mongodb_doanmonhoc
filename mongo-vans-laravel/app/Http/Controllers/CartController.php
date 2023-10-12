<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Product;
use DB;
use App\Http\Requests\CartRequest;
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
                    ->where('status', 'cart')->orderBy('created_at', 'DESC')->first();
        return response()->json($cart, 200);
    }
}
