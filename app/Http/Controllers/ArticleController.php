<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Article;

use Illuminate\Http\Request;
use Carbon\Carbon;

class ArticleController extends Controller
{
    public function index()
    {
    	$articles = Article::latest()->published()->get();
    	return view('articles.index', compact('articles'));
    }

    public function show($id)
    {
    	$article = Article::findOrFail($id);
    	return view('articles.show', compact('article'));
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(Requests\StoreArticleRequest $request)
    {
        $input = $request->all();
        $input['intro'] = mb_substr($request->get('content'), 0, 64);
        Article::create($input);
        return redirect('articles');
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        return view('articles.edit', compact('article'));
    }

    public function update(Requests\StoreArticleRequest $request, $id)
    {
        $article = Article::findOrFail($id);
        $article->update($request->all());
        return redirect('articles');
    }
}
