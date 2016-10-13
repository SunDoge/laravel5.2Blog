<?php

namespace App\Services;

use App\Post;

class FullTextSearch
{
    public function search($keyword)
    {
        $posts = Post::search($keyword)->with('tags')
            ->where('published_at', '<=', Carbon::now())
            ->where('is_draft', 0)
            ->orderBy('published_at', 'desc')
            ->simplePaginate(config('blog.posts_per_page'));

        return [
            'title' => 'Results',
            'subtitle' => $keyword,
            'posts' => $posts,
            'page_image' => config('blog.page_image'),
            'meta_description' => config('blog.description'),
            'reverse_direction' => false,
            'tag' => null
        ];
    }
}