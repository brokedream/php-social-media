<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Image;
use Auth;
use App\UserInfo;
use App\Blog;
use App\BlogArticle;
class RegisterController extends Controller
{
    //
    public function GetRegister(){
		return view('member.register');
	}
	public function PostRegister(Request $rq){
		$this->customValidate($rq);
			$user=$rq->except('avatar');
			$user['password']=bcrypt($rq->input('password'));
		if ($rq->hasFile('avatar')) {
			$avatar=$rq->file('avatar');
			$filename=$rq->account.'.'.$avatar->getClientOriginalExtension();
			Image::make($avatar)->resize(70,null, function ($constraint) {
    $constraint->aspectRatio();
})->save( public_path('/user/avatars/'.$filename ));
			Image::make($avatar)->resize(315,null, function ($constraint) {
    $constraint->aspectRatio();
})->save( public_path('/user/origin-avatars/'.$filename ));
			$user['avatar']=$filename;


			// $user->avatar=$filename;
			// $user->save();
			# code...
		}
			$user=User::create($user);
	
			$userinfo=new Userinfo(['online'=>\Carbon\Carbon::now(),'ip'=>$rq->ip()]);
			$user->userinfo()->save($userinfo);
			$blog=new Blog(['user_id'=>$user->id]);
			$blog->save();


			Auth::login($user);	
			return redirect()->route('index');
	}



	public function customValidate($rq){
		$this->validate($rq,[
			'account'=>'required|unique:users|max:255',
			'email'=>'unique:users|max:255|required|email',
			'password'=>'min:5|max:255|required',
			'company'=>'max:255|required',
			'age'=>'required|integer',
			'username'=>'required|max:3',
			'designer'=>'required|max:9',
			'gender'=>'required',
			'location'=>'required',
			'zodiac'=>'required',
			'avatar'=>'required',
			],
			[
				'account.required'=>'你沒有填寫帳號',
				'account.unique'=>'已經有這個帳號存在',
				'email.unique'=>'已經有這個信箱存在',
				'email.email'=>'要有email格式',
				'email.required'=>'你沒有填寫email',
				'password.min'=>'密碼最至少5個字',
				'password.required'=>'你沒有填寫密碼',
				'username.max'=>'姓名最多只有3個字',
				'username.required'=>'姓名最多只有3個字',
				'designer.max'=>'設計師名稱英文最多9個字/中文7個字',
				'designer.required'=>'設計師名稱沒填寫',
				'age.required'=>'年紀沒填寫',
				'company.required'=>'公司/學校沒填寫',
				'gender.required'=>'性別沒填寫',
				'location.required'=>'居住地沒填寫',
				'zodiac.required'=>'星座沒填寫',
				'avatar.required'=>'照片沒上傳',

			]
			);

	}
}
