<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Article;
use Carbon\Carbon;
use App\Category;
use App\Tag;

class FrontController extends Controller
{

    public function __construct()
    {
        Carbon::setLocale('es'); // idioma español
    }

    public function index()
    {
        $articles = Article::orderBy('id', 'DESC')->paginate(4);
        //dd($articles);
        $articles->each(function($articles){
            $articles->category;
            $articles->images;
        });

        return view('front.index')
            ->with('articles', $articles);
    }

    public function searchCategory($name)
    {
        //dd($name);
        // llamamos al scope, y nos traemos los datos
        $category = Category::SearchCategory($name)->first();
        $articles = $category->articles()->paginate(4);
        //dd($articles);
        $articles->each(function($articles){
            $articles->category;
            $articles->images;
        });

        return view('front.index')
            ->with('articles', $articles);

    }

    public function searchTag($name)
    {
        $tag = Tag::SearchTag($name)->first();
        //dd($tag);
        $articles = $tag->articles()->paginate(4);
        $articles->each(function($articles){
            $articles->category;
            $articles->images;
        });

        return view('front.index')
            ->with('articles', $articles);
    }

    public function viewArticle($slug)
    {
        $article = Article::findBySlugOrFail($slug);
        //dd($article);
        $article->category;
        $article->user;
        $article->tags;
        $article->images;

        return view('front.article')
            ->with('article', $article);
    }

}
