<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;
    $faker = Faker\Factory::create('zh_TW');
    return [
        'account' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'company'=>$faker->company,
        'age'=> $faker->numberBetween(20,50),
        'status'=>$faker->numberBetween(0,1),
        'username'=>$faker->name,
        'designer'=>$faker->name,
        // 'ip'=>$faker->ipv4,
        'location'=>$faker->country(),

    ];
});

$factory->define(App\UserInfo::class,
    function(Faker\Generator $faker){

    return [
        'ip'=>$faker->ipv4,
        'online'=>Carbon\Carbon::now()

    ];

});

$factory->define(App\Blog::class,
    function(Faker\Generator $faker){
    $faker = Faker\Factory::create('zh_TW');

    return [
        'blog_title'=>$faker->userName

    ];

});
$factory->define(App\BlogArticle::class,

    function(Faker\Generator $faker){
    $faker = Faker\Factory::create('zh_TW');

        return [
            'title'=>$faker->sentence,
            'content'=>$faker->paragraph,
            'article_site'=>$faker->unique()->randomNumber()
        ];
    }
    );
$factory->define(App\BlogArticleReply::class,

    function(Faker\Generator $faker){
    $faker = Faker\Factory::create('zh_TW');

    

        return [
            'content'=>$faker->sentence,
            'private'=>rand(0,1),
            'user_id'=>rand(1,15),
           
        ];
    }
    );
$factory->define(App\Mood::class,function(Faker\Generator $faker){


    return [
        'content'=>$faker->sentence
    ];

});
// discuss
$factory->define(App\DiscussArticle::class,function(Faker\Generator $faker){
    return [
        'title'=>$faker->sentence,
        'content'=>$faker->paragraph
    ];
});
        // reply


$factory->define(App\DiscussArticleReply::class,function(Faker\Generator $faker){
    return [
        'content'=>$faker->paragraph,
        'user_id'=>rand(1,10)
    ];
});
// gallery article
$factory->define(App\GalleryArticle::class,function(Faker\Generator $faker){
    return [
        'content'=>$faker->paragraph,
        'title'=>$faker->sentence,
        'image'=>$faker->imageUrl($width=640,$height=480),
        'description'=>$faker->sentence
    ];
});
$factory->define(App\GalleryReply::class,function(Faker\Generator $faker){
    return [
        'content'=>$faker->paragraph,
        'user_id'=>rand(1,15)
    ];
});
$factory->define(App\Status::class,function(Faker\Generator $faker){
    return [
    'content'=>$faker->sentence
    ];
});