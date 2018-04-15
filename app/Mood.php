<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mood extends Model
{
	protected $table='mood';
	protected $fillable=['user_id','content'];
	public $timestamps=true;



	public function user(){
		return $this->belongsTo('App\User');
	}
    //
}
