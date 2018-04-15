<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscussArticleTag extends Model
{
    //
    protected $table='discuss_article_tags';
    public function article(){
    	return $this->belongsToMany('App\DiscussArticle','discuss_article_tags_pivot','article_tags_id','discuss_article_id');
    }
    public function have_tag_user(){
    	return $this->belongsTo('App\User');
    }
}
