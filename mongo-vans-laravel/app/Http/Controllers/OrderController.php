<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Product;
use DB;
use Carbon\Carbon;
use MongoDB\BSON\ObjectID;

class OrderController extends Controller
{
    public function show(Request $request){
        $role = $request->user()->role;
        if($role=="admin"){
            $data = Bill::where('status', '!=', 'cart')
            ->when($request->filled('status'), function ($query) use ($request) {
                $status = $request->query('status');
                switch ($status) {
                    case 'intransit':
                        return $query->where('status', 'in transit');
                    case 'failed':
                        return $query->where('status', 'failed');
                    case 'completed':
                        return $query->where('status', 'completed');
                    case 'confirmationpending':
                        return $query->where('status', 'confirmation pending');
                    case 'processing':
                        return $query->where('status', 'processing');
                }
            })
            ->orderBy('updated_at', 'DESC')
            ->orderBy('status')
            ->paginate(5)->withQueryString();

            return response()->json($data, 200);
        }
        else return response()->json(['status'=> 404, 'message'=>'Bạn không có quyền này!'], 404);
    }

    public function getItemsByID($id, Request $request){
        $role = $request->user()->role;
        if($role=="admin"){
            $data = Bill::raw((function($collection) use($id) {
                return $collection->aggregate([
                    [
                        '$match' => [
                                '_id' => new ObjectID($id)]
                    ],
                    [
                        '$unwind' => '$items'
                    ],
                    [
                        '$project' => [
                            '_id' => 0,
                            'productname' => '$items.productname',
                            'image' => '$items.image',
                            'price' => '$items.price',
                            'quantity' => '$items.quantity',
                            'amount' => '$items.amount',
                            'size' => '$items.size',
                        ]
                    ],
                ]);
            }));
            return response()->json($data, 200);
        }
        else return response()->json(['status'=> 404, 'message'=>'Bạn không có quyền này!'], 404);
    }

    public function editOrder(Request $request, $id){
        $role = $request->user()->role;
        if($role=="admin"){
            $cart = Bill::where('_id', $id)->first();
            if($cart->status=='confirmation pending'){
                $cart->update(['status'=>'processing']);
            }
            else{
                if(!$request->status){
                    return response()->json(['status'=> 422, 'errors'=> ['OrderStatus' => 'Vui lòng chọn trạng thái đơn hàng!']], 422);
                }
                else{
                    $cart->update(['status'=>$request->status]);
                }
            }
            return response()->json(['status'=> 200, 'message'=>'Cập nhật thành công!'], 200);
        }
        else return response()->json(['status'=> 404, 'message'=>'Bạn không có quyền này!'], 404);
    }
}
