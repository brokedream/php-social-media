<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DiscussArticle;  
use App\DiscussArticleTag;
use App\DiscussArticleReply;
use Auth;
use DB;
use DOMDocument;
class DiscussController extends Controller
{
    //

    public function getDiscussArticle($id,$category=null){
    	$article=DiscussArticle::find($id);
    	$replies=DiscussArticleReply::with('discussReplytUser')->where('article_id',$article->id)->get();
    	$tags=$this->getTags();
		return view('discuss.discuss-content',compact('article','replies','tags'));

    }

    public function postDiscussArticle(Request $rq){

    	$this->validate($rq,[
    			'title'=>'required',
    			'content'=>'required',

    		]);
    	if(!Auth::check()){
    		$rq['user_id']=1;
    	}else{
            $rq['user_id']=Auth::id();
        }
        
    	$rq['image']=str_replace('http','https',$rq['image']);
    	$rq['content']=clean($rq->input('content'));
    	$article=new DiscussArticle($rq->except('category'));
    	$article->save();
    	$article->tag()->attach($rq->input('category'));
    	return redirect()->back();
    }


    public function postDiscussArticleReply(Request $rq ,$article_id){
    		$this->validate($rq,[
    			'content'=>'required',
    		]);
    	if(!Auth::check()){
    		$rq['user_id']=1;
    	}else{
    		$rq['user_id']=Auth::user()->id;

    	}
    	$rq['article_id']=$article_id;
    	DiscussArticleReply::create($rq->all());
    	return redirect()->back();
    }
}
