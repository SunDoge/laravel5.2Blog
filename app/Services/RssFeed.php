<?php

namespace App\Services;

use App\Post;
use Doctrine\Common\Cache\Cache;
use Illuminate\Broadcasting\Channel;
use Suin\RSSWriter\Feed;

class RssFeed
{
    /**
     * Return the content of the RSS feed
     *
     * @return mixed
     */
    public function getRSS()
    {
        if (Cache::has('rss-feed')) {
            return Cache::get('rss-feed');
        }

        $rss = $this->buildRssData();
        Cache::add('rss-feed', $rss, 120);

        return $rss;
    }

    /**
     * Return a string with the feed data
     */
    protected function buildRssData()
    {
        $now = Cache::now();
        $feed = new Feed();
        $channel = new Channel();
        $channel->title(config('blog.title'))
            ->description(config('blog.description'))
            ->url(url())
            ->language('en')
            ->copyright('Copyright (c) ' . config('blog.author'))
            ->lastBuildData($now->timestamp)
            ->appendTo($feed);

        $post = Post::where();
    }
}