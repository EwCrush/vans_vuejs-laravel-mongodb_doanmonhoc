<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Product;
use MongoDB\BSON\UTCDateTime;
use App\Http\Requests\CommentRequest;
use DB;
use Carbon\Carbon;


class CommentsController extends Controller
{
    public function showAllComments($id, Request $request){
        $order = $request->query('order');
        $data = Comment::raw((function($collection) use($id, $order) {
            if($order=='like'){
                $sortField = 'likecount';
            }
            else if ($order=='date'){
                $sortField = 'date';
            }
            else $sortField = 'product';
            return $collection->aggregate([
              [
                '$lookup' => [
                  'from' => 'users',
                  'localField' => 'user',
                  'foreignField'=> '_id',
                  'as' => 'user'
                ]
                ],
                [
                    '$unwind' => '$user'
                ],
                [
                    '$match' => [
                        'product' => (int)$id,
                    ]
                ],
                [
                    '$project' => [
                        '_id' => 1,
                        'product' => 1,
                        'text' => 1,
                        'likes' => 1,
                        'reply' => 1,
                        'user' => '$user._id',
                        'userfullname' => '$user.fullname',
                        'username' => '$user.username_account',
                        'avatar' => '$user.avatar',
                        'likecount' => ['$size' => '$likes.user'],
                        'date' => ['$dateToString' => ['format' => '%Y-%m-%d %H:%M:%S', 'date' => '$created_at', 'timezone' => 'Asia/Ho_Chi_Minh']],
                    ]
                ],
                [
                    '$sort' => [$sortField => -1]
                ]
            ]);
       }));

        return response()->json($data);
    }

    public function like(Request $request, $id){
        $user = $request->user()->_id;
        $comment = Comment::where("_id", $id)->first();
        if($comment){
            $field = Comment::where("_id", $id)->where('likes', 'elemMatch', ['user'=>$user])->first();
            //huy like
            if($field){
                Comment::where("_id", $id)
                    ->pull('likes', array( 'user' => $user));
                return response()->json(['status'=> 200, 'message'=>'Đã hủy like'], 200);
            }
            //like
            else{
                Comment::where("_id", $id)
                    ->push('likes', array( 'user' => $user ));
                return response()->json(['status'=> 200, 'message'=>'Đã like'], 200);
            }
        }
        else {
            return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại!'], 404);
        }
    }

    public function storeComment($id, CommentRequest $request){
        $user = $request->user()->_id;
        $reply = $request->reply ? $request->reply :  "";
        $product = Product::where("_id", (int)$id)->first();
        if($product){
            $date = Carbon::now()->format('Y-m-d\TH:i:s.uP');
            $date = str_replace('"', '', $date);

            Comment::create([
                "product" => (int)$id,
                "user" => $request->user()->_id,
                "text" => $request["text"],
                "likes" => [],
                "reply" => $reply
            ]);
            return response()->json(['status'=> 200, 'message'=>'Đã bình luận'], 200);
        }
        else {
            return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại!'], 404);
        }
    }

    public function deleteComment($id, Request $request){
        $user = $request->user()->_id;
        $comment = Comment::where("_id", $id)->first();
        if($comment){
            if($comment->user==$user){
                $replies = Comment::where("reply", $id)->get();
                foreach ($replies as $reply) {
                    $reply->delete();
                }
                $comment->delete();
                return response()->json(['status'=> 200, 'message'=>'Đã xóa bình luận'], 200);
            }
            else return response()->json(['status'=> 200, 'message'=>'Không thể xóa'], 200);
        }
        else {
            return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại!'], 404);
        }
    }

    public function editComment($id, CommentRequest $request){
        $user = $request->user()->_id;
        $comment = Comment::where("_id", $id)->first();
        if($comment){
            if($comment->user==$user){
                $comment->update([
                    "text" => $request["text"],
                ]);
                return response()->json(['status'=> 200, 'message'=>'Đã xóa bình luận'], 200);
            }
            else return response()->json(['status'=> 404, 'message'=>'Không thể xóa'], 200);
        }
        else {
            return response()->json(['status'=> 404, 'message'=>'Dữ liệu không tồn tại!'], 404);
        }
    }
}
