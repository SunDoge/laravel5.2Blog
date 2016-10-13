<?php

namespace App\Http\Controllers;

use App\Jobs\BlogIndexData;
use App\Post;
use App\Services\FullTextSearch;
use App\Services\RssFeed;
use App\Services\SiteMap;
use App\Tag;
use Illuminate\Http\Request;

use App\Http\Requests;

class BlogController extends Controller
{
    public function welcome()
    {
        return redirect('blog');
    }

    public function index(Request $request)
    {
        $tag = $request->get('tag');
        $data = $this->dispatch(new BlogIndexData($tag));
        $layout = $tag ? Tag::layout($tag) : 'blog.layouts.index';
//        dd($data);
        return view($layout, $data);
    }

    public function show(Request $request, $slug)
    {
        $post = Post::with('tags')->whereSlug($slug)->firstOrFail();
        $tag = $request->get('tag');
        if ($tag) {
            $tag = Tag::whereTag($tag)->firstOrFail();
        }

        return view($post->layout, compact('post', 'tag', 'slug'));
    }

    public function siteMap(SiteMap $siteMap)
    {
        $map = $siteMap->getSiteMap();

        return response($map)->header('Content-type', 'text/xml');
    }

    public function getTitles($query = null)
    {
        $titles = Post::select('title', 'slug');
        if ($query) {
            $titles = $titles->where('title', '%' . $query . '%');
        }
        $titles = $titles->get()->toArray();
        $result = [];

        foreach ($titles as $key => $title) {
            $result += [$title['title'] => url('/blog') . '/' . $title['slug']];
        }

        return response()->json($result);
    }

    public function rss(RssFeed $feed)
    {
        $rss = $feed->getRSS();

        return response($rss)->header('Content-type', 'application/rss+xml');
    }

    public function search(Request $request)
    {
        $search = new FullTextSearch();
        $data = $search->search();
        $layout = 'blog.layouts.index';
    }
}
