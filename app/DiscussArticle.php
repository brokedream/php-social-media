<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscussArticle extends Model
{
    //
    protected $table='discuss_article';
    protected $fillable=['user_id','content','article_tags_id','title','image'];
    public $timestamps=true;
    public function user(){
    	return $this->belongsTo('App\User');
    }
    public function tag(){
    	return $this->belongsToMany('App\DiscussArticleTag','discuss_article_tags_pivot','discuss_article_id','article_tags_id')->withTimestamps();
    }
    public function replies(){
            return $this->hasMany('App\DiscussArticleReply','article_id','id');
    }

}
