<?php

namespace App\Http\Controllers;
use App\User;
use DB;
use Illuminate\Http\Request;
use App\BlogArticle;
use Auth;
use Session;
use Cookie;
use App\Blog;
use App\BlogArticleReply;
use File;
class BlogController extends Controller
{


    //

 
    public function getBlogArticle($user,$article_site){

        $stuff=$this->getBlogStuff($user);
        ;
    	$article=BlogArticle::where('article_site',$article_site)
    	->first()
    	;
        $replies= $this->getArticleReply($article->id);


          if(!Cookie::has(md5('visited'.$article_site))){
       $article->watch_count++;
            $article->save();
        Cookie::queue(md5('visited'.$article_site), 'shit', 720);
}
    		return view('blog.article')->
		with('blog',$stuff['blog'])->
		with('user',$user)->
		with('avatar',$stuff['avatar'])->
		with('visiters',$stuff['visiters'])->
		with('article',$article)->
		with('articles_latest',$stuff['articles_latest'])->
        with('replies',$replies)->
        with('recent_replies',$stuff['recent_replies'])->
        with('user_entity',$stuff['user_entity']);


		
    
    }
  



    public function getBlogStuff($user){



        
    	$useraccount=User::where('account',$user)->first();

    	$visiters=$useraccount->getLatestVisiter();
    	$user_id=$useraccount->id;

    $recent_replies=$this->getRecenetReply($user_id);
         if(Auth::check()&&$user_id!=Auth::user()->id){
            $useraccount->visitersOfMine()->attach(Auth::user()->id);
        }
    	if($useraccount->avatar){

    		$avatar=$useraccount->avatar;
    	}else{
    		$avatar='user.jpg';
    	}
		$articles_latest=BlogArticle::where('user_id',
			$user_id)->orderBy('created_at','desc')->select('title','hint','article_site')->limit(9)->get();

          if(!Cookie::has(md5('visited'.$user.'blog'))){
         DB::table('blog')->where('user_id',$user_id)->increment('visited_count');
        Cookie::queue(md5('visited'.$user.'blog'), 'shit', 720);

    }
 
    	$blog=$useraccount->blog;
    	return ['blog'=>$blog,'avatar'=>$avatar,'user_id'=>$user_id,'articles_latest'=>$articles_latest,'visiters'=>$visiters,'recent_replies'=>$recent_replies,'user_entity'=>$useraccount];
    }
 
    public function getRecenetReply($user_id){

        return   $recent_replies=BlogArticleReply::with(['user','article'])->where('blog_user_id',$user_id)->orderBy('created_at','desc')->limit('9')->get();

    }




    public function getChangeBlogStyle(){
       $blog =Blog::where('user_id',Auth::user()->id)->first();
        return view('blog.styleblog',compact('blog'));
    }
     public function postChangeBlogStyle(Request $rq){
        $user=Auth::user();
        $blog=Blog::where('user_id',$user->id);
        $blog->update($rq->except('_token'));
       return redirect()->route('blog::article-list',['user'=>$user->account]);

}
    public function getWriteCss($user_account){

       
    $css=file_get_contents(public_path('user/css/'.$user_account.'/'.'custom-blog.css'));
        return view('blog.write-style',compact('css'));
    }
    public function postWriteCss(Request $rq){
        if((bool)$rq['restore']){
        $css=file_get_contents(public_path('user/css/custom-blog.css'));
        file_put_contents(public_path('user/css/'.Auth::user()->account.'/'.'custom-blog.css'),$css);
        }else{
             file_put_contents(public_path('user/css/'.Auth::user()->account.'/'.'custom-blog.css'),$rq['css']);

             
        }
         return redirect()->route('blog::article-list',['user'=>Auth::user()->account]); 
        

    }


}