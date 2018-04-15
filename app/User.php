<?php

namespace App;
use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            'account',
            'email',
            'password',
            'company',
            'age',
            'username',
            'designer',
            'gender',
            'location',
            'zodiac',
            'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function userinfo(){
        return $this->hasOne('App\UserInfo');

    }
    public function blog(){
        return $this->hasOne('App\Blog');

    }
    public function blog_article(){
        return $this->hasMany('App\BlogArticle');
    }
    public function visitersOfMine(){


         return   $this->belongsToMany('App\User','visit','user_id','visiter_id')->withPivot('created_at','visiter_id','id')->limit(30);
    }
    public function getLatestVisiter(){

       return $this->visitersOfMine()->orderBy('pivot_created_at','desc')->get()->unique();
    }
    public function replies(){
        return $this->hasMany('App\BlogArticleReply');
    }
    public function owner_replies(){
          return $this->hasMany('App\BlogArticleReply','blog_user_id','id');
    }
    public function mood(){
        return $this->hasMany('App\Mood');
    }
    public function write_discuss_article(){
         return $this->hasMany('App\DiscussArticle');

    }
    public function write_discuss_reply(){
       return  $this->hasMany('App\DiscussArticleReply');
    }
    public function gallery(){
        return $this->hasMany('App\GalleryArticle');
    }
    public function write_gallery_reply(){
        return $this->hasMany('App\GalleryReply');
    }
    public function posted_like(){
        return $this->hasMany('App\GalleryLike','user_id','id');
    }
// friend

    // 我是user_id 也就是被邀請人 取得邀請我的人資料關聯
    // (包括已經成為朋友)
    public function friendOfMine(){
        return $this->belongsToMany('App\User','friends','user_id','friend_id');
    }
    // 我是friend_id也就是邀請人 取得我想邀請人的資料關聯
    // (包括已經成為朋友)
   public function friendOf(){
        return $this->belongsToMany('App\User','friends','friend_id','user_id');
    }
    // 有哪些已經成為朋友朋友
    public function friends(){
        return $this->friendOfMine()->wherePivot('accepted',true)->get()
        ->merge($this->friendOf()->wherePivot('accepted',true)->get());
        ;
    }
    // 有朋友邀請(取得還未成為朋友的人資料//我是user_id)
    public function friendRequests(){
        return $this->friendOfMine()->wherePivot('accepted',false)->get();
    }
    // 我是邀請人(取得被我邀請人的資料)
    public function friendRequestsPending(){
        return $this->friendOf()->wherePivot('accepted',false)->get();
    }
// 傳入的這個人是否已經被我邀請了,有的話true正等待他接受
    public function hasFriendRequestPending($usersId){
        return  $this->friendRequestsPending()->whereIn('id',$usersId);
    }
// 傳入的這個人是否已經邀請我了,有的話true正等待我接受
    public function hasFriendRequestReceived($usersId){
        return  $this->friendRequests()->whereIn('id',$usersId);
    }
    // 發出邀請
    public function addFriend(User $user){
        return $this->friendOf()->attach($user->id);
    }   
    // 接受邀請
    public function acceptFriendRequest(User $user){
        return $this->friendRequests()->where('id',$user->id)->first()->pivot->update(['accepted'=>true]);
    }
    // 我邀請的人跟邀請我的人已接受中查詢傳進來這個人是不是朋友，
    public function isFriendsWith($usersId){
        return $this->friends()->whereIn('id',$usersId);
    }

        // friend end 

    // status
    public function Write_status(){
        return $this->hasOne('App\Status','user_id','id');
    }


}
