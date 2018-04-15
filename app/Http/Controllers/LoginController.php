<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Auth;
use App\Userinfo;
use Session;
class LoginController extends Controller
{
    //
	public function getLogin(){
		return view('member.login');
	}

	public function postLogin(Request $rq){
			$this->validate($rq,[
			'account'=>'required|max:255',
			'password'=>'min:5|max:255|required',
		
			],
			[
				'account.required'=>'你沒有填寫帳號',
				'password.required'=>'你沒有填寫密碼',
				'password.min'=>'密碼至少5個字',

			]
			);
		if (Auth::attempt(['account'=>$rq->input('account'),'password'=>$rq->input('password')])) {
			$user=Userinfo::find(Auth::user()->id);
			$user->ip=$rq->ip();
			$user->online=\Carbon\Carbon::now();
			$user->save();
			return redirect()->route('index');
			# code...
		}else{
			return redirect()->back()->withErrors(['帳號密碼錯誤'])->withInput($rq->all());
		}
	}

	public function logout(){
		$userinfo=Userinfo::where('user_id',Auth::user()->id)->first();
		$userinfo->offline=\Carbon\Carbon::now();
		$userinfo->save();
		Session::flush();
		Auth::logout();
		return redirect()->route('index');
	}

}
