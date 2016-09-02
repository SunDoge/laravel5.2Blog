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
//        \Illuminate\Database\Eloquent\Model::unguard();
        \Illuminate\Database\Eloquent\Model::unguard();
        $this->call('TagTableSeeder');
        $this->call('PostTableSeeder');
        \Illuminate\Database\Eloquent\Model::reguard();
    }
}

class PostTableSeeder extends Seeder
{
    public function run()
    {
        // Pull all the tag names from the file
        $tags = \App\Tag::pluck('tag')->all();

        \App\Post::truncate();

        // Don't forget to truncate the pivot table
        \Illuminate\Support\Facades\DB::table('post_tag_pivot')->truncate();

        factory(\App\Post::class, 20)->create()->each(function ($post) use ($tags) {

            // 30% of the time don't assign a tag
            if (mt_rand(1, 100) <= 30) {
                return;
            }

            shuffle($tags);
//            var_dump($tags);
            $postTags = [$tags[0]];

            // 30% of the time we're assigning tags, assign 2
            if (mt_rand(1, 100) <= 30) {
                $postTags[] = $tags[1];
            }

            $post->syncTags($postTags);
        });
    }
}

class TagTableSeeder extends Seeder
{
    public function run()
    {
        \App\Tag::truncate();
        factory(\App\Tag::class, 5)->create();
    }
}