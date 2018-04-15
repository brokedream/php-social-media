<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
	protected $table='status';
	protected $fillable=['user_id','content'];
    //
    public function user(){
    	return $this->belongsTo('App\User');
    }
}