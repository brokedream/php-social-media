<?php

namespace App\Providers;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Search;
use View;
use DB;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
            Schema::defaultStringLength(191);
                \Carbon\Carbon::setLocale('zh-TW');
                $keywords=DB::table('searches')->select(DB::raw("COUNT(*),keyword"))->groupBy('keyword')->orderBy('COUNT(*)','desc')->limit(5)->get();
            View::share('keywords',$keywords);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
