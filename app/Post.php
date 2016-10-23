<?php

namespace App;

use App\Services\Markdowner;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use Searchable;

    protected $dates = ['published_at'];

    protected $fillable = [
        'title',
        'subtitle',
        'content_raw',
        'page_image',
        'meta_description',
        'layout',
        'is_draft',
        'published_at',
    ];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;

        if (!$this->exists) {

            if (preg_match("/[\x{4e00}-\x{9fa5}]/u", $value)) {
                $value = translateZHtoENG($value);
            }
//            $this->attributes['slug'] = str_slug($value);
            $this->setUniqueSlug($value, '');
        }
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag', 'post_tag_pivot');
    }

    protected function setUniqueSlug($title, $extra)
    {
        $slug = str_slug($title . '-' . $extra);

        if (static::whereSlug($slug)->exists()) {
            $this->setUniqueSlug($title, $extra + 1);
            return;
        }

        $this->attributes['slug'] = $slug;
    }

    public function setContentRawAttribute($value)
    {
        $markdown = new Markdowner();

        $this->attributes['content_raw'] = $value;

        $this->attributes['content_html'] = $markdown->toHTML($value);

    }

    public function syncTags(array $tags)
    {
        Tag::addNeededTags($tags);
//        dd( Tag::whereIn('tag', $tags)->pluck('id')->all());
        if (count($tags)) {
            $this->tags()->sync(
                Tag::whereIn('tag', $tags)->pluck('id')->all()
            );
            return;
        }

        $this->tags()->detach();
    }

    /**
     * Return the date portion of published_at
     */
    public function getPublishDateAttribute($value)
    {
        return $this->published_at->format('M-j-Y');
    }

    /**
     * Return the time portion of published_at
     */
    public function getPublishTimeAttribute($value)
    {
        return $this->published_at->format('g:i A');
    }

    /**
     * Alias for content_raw
     */
    public function getContentAttribute($value)
    {
        return $this->content_raw;
    }

    /**
     * Return URL to post
     *
     * @param Tag|null $tag
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url(Tag $tag = null)
    {
        $url = url('blog/' . $this->slug);
        if ($tag) {
            $url .= '?tag=' . urlencode($tag->tag);
        }

        return $url;
    }

    /**
     * Return array of tag links
     *
     * @param string $base
     * @return array
     */
    public function tagLinks($base = '/blog?tag=%TAG%')
    {
        $tags = $this->tags()->get();
        $return = [];
        foreach ($tags as $tag) {
            $url = str_replace('%TAG%', urlencode($tag->tag), $base);
            $return[] = '<a href="' . $url . '"><span class="label label-info">' . e($tag->tag) . '</span></a>';
        }

        return $return;
    }

    /**
     * Return next post after this one or null
     *
     * @param Tag|null $tag
     * @return mixed
     */
    public function newerPost(Tag $tag = null)
    {
        $query = static::where('published_at', '>', $this->published_at)
            ->where('published_at', '<=', Carbon::now())
            ->where('is_draft', 0)
            ->orderBy('published_at', 'asc');

        if ($tag) {
            $query = $query->whereHas('tags', function ($q) use ($tag) {
                $q->where('tag', $tag->tag);
            });
        }

        return $query->first();
    }

    /**
     * Return older post before this on or null
     *
     * @param Tag|null $tag
     * @return mixed
     */
    public function olderPost(Tag $tag = null)
    {
        $query = static::where('published_at', '<', $this->published_at)
            ->where('is_draft', 0)
            ->orderBy('published_at', 'desc');

        if ($tag) {
            $query = $query->whereHas('tags', function ($q) use ($tag) {
                $q->where('tag', $tag->tag);
            });
        }

        return $query->first();
    }

//    public function setMetaDescription($value)
//    {
////        if (!$this->meta_description) {
////            $this->attributes['meta_description'] = substr(strip_tags($value), 0, 155);
////            var_dump($this->attributes['meta_description']);echo '<br>'.$this->attributes['meta_description'];
////            dd($this->attributes['meta_description']);
////
////        }
//        $value = 'sdasdfadsfasdfasdf';
//        $this->attributes['meta_description'] = substr(strip_tags($value), 0, 155);
//    }

    public function setMetaDescriptionAttribute($value)
    {
        $html = $this->attributes['content_html'];

        $this->attributes['meta_description'] = mb_substr(strip_tags($html), 0, 155, 'utf-8');

    }

}

