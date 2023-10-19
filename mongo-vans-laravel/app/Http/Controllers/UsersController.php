<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\EditProfileRequest;
use App\Http\Requests\SendMailRequest;
use App\Http\Requests\SendOTPRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Facades\Hash;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
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

    public function editUser(EditProfileRequest $request){
        $user = $request->user();
        $oldAvatar = $user->avatar;
        if ($user->email != $request->email) {
            $checkEmail = User::where('_id', '!=', $user->_id)->where('email', $request->email)->first();
            if ($checkEmail) {
                return response()->json(['status' => 422, 'errors' => ['email' => ['Email này đã tồn tại']]], 422);
            }
        }
        $updateData = [
            'fullname' => $request->fullname,
            'address' => $request->address,
            'phonenumber' => $request->phone,
            'email' => $request->email,
        ];
        if ($request->filename) {
            $updateData['avatar'] = $request->filename;
        }
        User::where('_id', $user->_id)->update($updateData);
        if($request->filename){
            return response()->json(['status' => 200, 'filename' => $oldAvatar, 'message' => 'Cập nhật thông tin thành công'], 200);
        }
        else {
            return response()->json(['status' => 200, 'message' => 'Cập nhật thông tin thành công'], 200);
        }
    }

    public function sendMail(SendMailRequest $request){
        $email = $request->email;
        $otp = $randomNumber = rand(100000, 999999);
        $user = User::where('email', $email)->first();
        $user->update(['OTP' => $otp]);


        $mail = new PHPMailer;
        $mail->isSMTP();                            // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';              // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                     // Enable SMTP authentication
        $mail->Username = 'vanvanvan1972001@gmail.com'; // your email id
        $mail->Password = 'dlvglztwuweyniic'; // your password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;     //587 is used for Outgoing Mail (SMTP) Server.

        $mail->setFrom('vanvanvan1972001@gmail.com');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Change your password";
        $mail->Body = '<div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2">
        <div style="margin:50px auto;width:70%;padding:20px 0">
          <div style="border-bottom:1px solid #eee">
            <a href="" style="font-size:1.4em;color: #3060FF;text-decoration:none;font-weight:600">VAULT VIETNAM</a>
          </div>
          <p style="font-size:1.1em">Xin chào,</p>
          <p>Đây là mã OTP để đặt lại mật khẩu của bạn, vui lòng giữ bí mật và không tiết lộ mã OTP này cho bên thứ 3.</p>
          <h2 style="background: #3060FF;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">'.$otp.'</h2>
          <p style="font-size:0.9em;">Trân trọng,<br/>Đội ngũ VAULT VIETNAM</p>
          <hr style="border:none;border-top:1px solid #eee" />
          <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
            <p>VAULT VIETNAM</p>
            <p>37/44 Trần Đình Xu, Cầu Kho, Quận 1, Thành phố Hồ Chí Minh</p>
          </div>
        </div>
      </div>';

        $mail->send();

        return response()->json(['status' => 200, 'message' => 'Đã gửi mã OTP đến địa chỉ Email của bạn',
                                'fullname' => $user->fullname, 'avatar' => $user->avatar], 200);

    }

    public function sendOTP(SendOTPRequest $request){
        $otp = $request->otp;
        $email = $request->email;
        $user = User::where('email', $email)->first();
        if($user->OTP == $otp){
            return response()->json(['status'=> 200, 'message'=>'OTP đúng'], 200);
        }
        else {
            $user->update(['OTP' => ""]);
            return response()->json(['status'=> 404, 'message'=>'OTP sai, vui lòng thử lại'], 404);
        }
    }

    public function resetPassword(ResetPasswordRequest $request){
       $email = $request->email;
       $otp = $request->otp;
       $user = User::where('email', $email)->first();
        if($user->OTP == $otp){
            $user->update([
                "OTP" => "",
                "password_account" => Hash::make($request["newpassword"]),
            ]);
            return response()->json(['status'=> 200, 'message'=>'Đặt lại mật khẩu thành công'], 200);
        }
        else {
            $user->update(['OTP' => ""]);
            return response()->json(['status'=> 404, 'message'=>'OTP sai, vui lòng thử lại'], 404);
        }
    }
}
