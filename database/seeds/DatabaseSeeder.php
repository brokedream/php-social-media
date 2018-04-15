

<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        factory(App\User::class,15)->create()->each(

            function($u){

         $u->userinfo()->save(factory(App\UserInfo::class)->make());
         $u->blog()->save(factory(App\Blog::class)->make());
     
         //使用者會有文章

         for($i=1;$i<15;$i++){
               $user_article=$u->blog_article()->save(
                factory(App\BlogArticle::class)->make());
                 //使用者文章會有回覆
                $reply=factory(App\BlogArticleReply::class)->make();

                $reply->blog_user_id=$u->id;
                 $user_article->replies()->save($reply);

         }

         $u->mood()->save(factory(App\Mood::class)->make());
         // disucss a

       

 $discuss_article=$u->write_discuss_article()->save(factory(App\DiscussArticle::class)->make());
        $discuss_article->tag()->attach(rand(1,10));
    // discuss reply
        $discuss_article->replies()->save(factory(App\DiscussArticleReply::class)->make());
       




                // gallery article
       $gallery_article= factory(App\GalleryArticle::class)->make();
        $u->gallery()->save($gallery_article);
// gallery reply
       $gallery_reply= factory(App\GalleryReply::class)->make();
        $gallery_article->ownReply()->save($gallery_reply);


// status
        $status=factory(App\Status::class)->make();
        $u->Write_status()->save($status);







        }
        );






    }
}
