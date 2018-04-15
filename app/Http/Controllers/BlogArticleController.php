<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BlogArticle;
use Auth;
use Image;
use Storage;
use Cache;
use App\Mood;
class BlogArticleController extends Controller
{
    //
    public function postArticle(Request $rq){
    		$this->validate($rq,[
    			'title'=>'required|max:16',
    			'content'=>'required|max:2000'
    			],[
    			'title.required'=>'沒填寫標題',
    			'content.required'=>'沒填寫標內容',
    			'content.max'=>'內容最多2000字'

    			]);
    		$account=Auth::user()->account;
    		$store_path=public_path('/user/blog_article_image/'.$account.'/');
    		$article=$rq->all();
    		if($rq->hasFile('image')){
    			$image=$rq->file('image');
    			$filename=\Carbon\Carbon::now()->format('Y-m-d').uniqid().'.'.$image->getClientOriginalExtension();
    			if(!file_exists($store_path)){
    				mkdir($store_path);
    			}
    			Image::make($image)->resize(390,null, function ($constraint) {
    $constraint->aspectRatio();
})->save( $store_path.$filename );
    		$article['image']=$filename;

    		}
    		$article['article_site']=md5(uniqid());
    		$article['content']=clean(nl2br($article['content']));
         	$article=new BlogArticle($article);

    	Auth::user()->blog_article()->save($article);
    	return redirect()->back();

    }


    public function getMoodMessage(){

        $moods=Mood::with('user')->orderBy('created_at','desc')->limit(5)->get();
        return $moods;
    }


    
}


