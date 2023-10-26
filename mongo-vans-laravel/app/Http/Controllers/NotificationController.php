<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Notification;
use App\Models\Comment;
use App\Models\User;

class NotificationController extends Controller
{
    public function show(Request $request){
        $notifies = Notification::where('to', $request->user()->_id)->orderBy('updated_at', 'DESC')->get();
        foreach ($notifies as $notify) {
            $comment = Comment::where('_id', $notify->comment)->first();
            $user = User::where('_id', $notify->from)->first();
            $notify->text = $comment->text;
            $notify->at = $comment->product;
            $notify->fullname = $user->fullname;
            $notify->avatar = $user->avatar;
            // $notify->save();
        }
        return response()->json($notifies, 200);
    }

    public function read(Request $request, $id){
        $notify = Notification::where('_id', $id)->first();
        if($notify->to == $request->user()->_id && $notify->status=='unread'){
            $notify->timestamps = false;
            $notify->update(['status' => 'seen']);
            return response()->json(['status'=> 200], 200);
        }
    }
}
