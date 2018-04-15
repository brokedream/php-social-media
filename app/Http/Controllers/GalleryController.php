<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GalleryArticle;
use Auth;
use App\User;
use Image;
use App\GalleryReply;
use App\GalleryLike;
use App\GalleryShare;
use Cookie;
use Route;
class GalleryController extends Controller
{
    //
    public function getGallery(){
$articles=$this->getArticles();
if(Auth::check()){
  $auth_user=User::find(Auth::user()->id);
}else{
  $auth_user='';
}

if(Auth::check()){


$users=[];
foreach($articles as $article){

  array_push($users,$article->user->id);

}


  $isFriendArray=Auth::user()->isFriendsWith($users);
$hasFriendRequestPendingArray=Auth::user()->hasFriendRequestPending($users);
$hasFriendRequestReceivedArray=Auth::user()->hasFriendRequestReceived($users);


}



    return view('index',compact('articles','auth_user','hasFriendRequestPendingArray','isFriendArray','hasFriendRequestReceivedArray'));
    }
    public function getArticles(){
    	return GalleryArticle::with(['user','ownReply.user','ownLike','ownShare'])->orderBy('created_at','desc')->paginate(15);
    }

    public function postPhotoArticle(Request $rq){

 		$useraccount='';

      $this->validate($rq,[
        'title'=>'required',
        'content'=>'required',
        'description'=>'required',

        ]);

    	if(Auth::check()){
 		$rq['user_id']=Auth::user()->id;
 		$useraccount=Auth::user()->account;
 		}else{
 		$rq['user_id']=1;	
 		$useraccount=User::find(1)->account;

 		}
if ($rq->hasFile('little_image')) {


		$store_path=public_path('/user/gallery/'.$useraccount);
		if(!file_exists($store_path)){
    				mkdir($store_path);
    			}
			$little_image=$rq->file('little_image');
			$filename=uniqid().'.'.$little_image->getClientOriginalExtension();

			Image::make($little_image)->resize(350,null, function ($constraint) {
    $constraint->aspectRatio();
})->save( public_path('/user/gallery/'.$useraccount.'/'.$filename ));
	
		$rq['image_xs']=$filename;
		}else{
 		dd('no');

		}


 		$rq['image']=str_replace('http','http',$rq->input('image'));
 		GalleryArticle::create($rq->except('_token','little_image'));
 		return redirect()->back();


    }
    public function	postReply(Request $rq){
    	if(Auth::check()){
 		$rq['user_id']=Auth::user()->id;
 		}else{
 		$rq['user_id']=1;	

 		}
    	GalleryReply::create($rq->all());
       $user=User::find($rq['user_id']);
    	return response()->json([$user]);
    }

    public function postLike(Request $rq){
      $article_id=$rq->input('article_id');
      $user='';

        
             if(Auth::check()){
               $user_id= $rq['user_id']=Auth::user()->id;
                if($like=GalleryLike::where('article_id',$article_id)->where('user_id', $user_id)->first()){
               $like->delete();
                      }else{
                 GalleryLike::create($rq->except('_token'));
                        

                      }
            }else{
                if(Cookie::has('article'.$article_id)){
                        
                }else{
                GalleryLike::create($rq->except('_token'));
                    Cookie::queue('article'.$article_id,true,720);
                }
            }
             
            return response()->json(['ok']);
      
     
    }


}

