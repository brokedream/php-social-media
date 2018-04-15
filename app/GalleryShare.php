<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GalleryShare extends Model
{
	protected $table='gallery_share';
	protected $fillable=['article_id','count'];
    //
}
