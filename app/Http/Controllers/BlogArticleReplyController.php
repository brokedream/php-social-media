<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;

use App\BlogArticleReply;
class BlogArticleReplyController extends Controller
{
    //
 	public function postReply($user,$article_id,Request $rq){
 		$this->validate($rq,[
 			'content'=>'required'
 			]);
 		$blog_user=User::where('account',$user)->first();
 		if(Auth::check()){
 		$rq['user_id']=Auth::user()->id;
 		}else{
 		$rq['user_id']=1;	
 		}
 		$rq['blog_article_id']=$article_id;
 		$rq['blog_user_id']=$blog_user->id;
 		$rq->input('private')=='on'?$rq['private']=true:$rq['private']=false;
 		BlogArticleReply::create($rq->all());
 		return redirect()->back();
 	}   
}
