<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Search;
class SearchController extends Controller
{
    //
    public function postKeyword(Request $rq){
    	Search::create(['keyword'=>$rq['keyword']]);
    	return redirect()->back();
    }
}
