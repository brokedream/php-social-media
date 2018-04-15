<?php
use App\Video;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// gallery

Route::get('/', 'GalleryController@getGallery')->name('index');
Route::post('/photo/post','GalleryController@postPhotoArticle')->name('gallery-article-post');
Route::post('/gallery/comment/post','GalleryController@postReply')->name('gallery-reply');
Route::post('/gallery/photo/like','GalleryController@postLike')->name('like-photo');
Route::get('/gallery/photo/article/{article_id?}','GalleryController@getPhotoArticle')->name('watch-photo');

Route::get('/gallery/photo/article/{article_id?}/detail','GalleryController@RenderArticleDetail')->name('get-photo-detail');
// 個人相簿
Route::get('/gallery/album/{user_account}','GalleryController@RenderUserGallery')->name('get-user-album');
Route::group(['as'=>'blog::','prefix'=>'blog'],function(){
		

// protect Auth
		Route::group(['middleware'=>'auth'],function(){
		// Mood post
			Route::post('/mood','MoodController@postMood')
			->name('postMood');


			// post article
				Route::post('/{user}/post','BlogArticleController@postArticle')->name('post_article');
		// style blog
				Route::get('{user}/custom','BlogController@getChangeBlogStyle')
				->name('custom-get')
				;
			Route::post('{user}/custom','BlogController@postChangeBlogStyle')
				->name('custom-edit');
		//css
				Route::get('{user_account}/css','BlogController@getWriteCss')->name('get-css');
				Route::post('{user_account}/css','BlogController@postWriteCss')->name('post-css');
		});


		Route::get('/latest-article', 'BlogArticleController@getLatestArticle')->name('latest-article');
		Route::get('/{user}','BlogController@getBlog')->name('article-list');
		Route::get('/{user}/{article_site}','BlogController@getBlogArticle')->name('article');
		Route::get('/{user}/{article_id}/decrypt','BlogController@getDecryptArticle')->name('decrypt');


			// reply article
			Route::post('/{user}/{article_id}/reply','BlogArticleReplyController@postReply')->name('reply');


			// latest-art
		Route::get('/',function(){
			return redirect()->route('blog::latest-article');
		});
			


		});


	


// discuss
Route::group(['prefix'=>'discuss','as'=>'discuss::'],function(){
		Route::get('/list/{category_id?}',
		'DiscussController@getDiscussList'
	)->name('list');

		
		Route::get('/article/{id}',
			'DiscussController@getDiscussArticle'
	)->name('article');
		Route::post('/article/post','DiscussController@postDiscussArticle')->name('post-article');
		// reply
			Route::post('/article/{id}/reply','DiscussController@postDiscussArticleReply')->name('post-reply');


	
});

// stream-video
Route::get('stream-video',function(){

	$videos=Video::latest()->limit(24)->get();
	return view('video.stream-video-grid',compact('videos'));
})->name('stream-video');
Route::post('stream-video',function(Request $rq){

	Video::create($rq->except('_token'));
	return redirect()->back();
})->name('stream-video');
// guest register login
Route::group(['middleware'=>'guest'],function(){
	Route::get('register','RegisterController@GetRegister')
	->name('register');
	;
	Route::post('register','RegisterController@PostRegister')
	->name('post-register');
	;


	Route::get('login','LoginController@getLogin')
	->name('login')
	;
	Route::post('login','LoginController@postLogin')
	->name('Postlogin')
	;

});
// friend
Route::group(['prefix'=>'friend','as'=>'friend::'],


	function(){

		Route::group(['middleware'=>'auth'],function(){

		Route::get('/accept/{friend_id?}','FriendController@acceptFriend')->name('accept');

		Route::get('/addFriend/{invited_account?}','FriendController@addFriend')
			->name('send-invite');

	Route::get('/invite/{user_account}','FriendController@getInvitePage')
		->name('invite');
		;
		});
		Route::get('/list/{user_account}','FriendController@getIndex')->name('user-friend');
		Route::post('/user/status','FriendController@postStatus')->name('post-status');
	
		
});

Route::group(['middleware'=>'auth'],function(){
		Route::get('logout','LoginController@logout')
	->name('logout')
	;
});
// search
Route::post('/search','SearchController@postKeyword')->name('post-search');