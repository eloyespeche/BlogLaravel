<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Category;
use App\Tag;
use App\Article;
use App\Image;
use illuminate\Support\Facades\Redirect;
use Laracasts\Flash\Flash;
use App\Http\Requests\ArticleRequest;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Obtenemos todos los artículos, mostrando
        // 5 artículos por cada página
        $articles = Article::Search($request->title)
            ->orderBy('id', 'DESC')
            ->paginate(5);
        // llamamos a las relaciones
        $articles->each(function($articles){
            // miramos en el modelo para saber que
            // relaciones tenemos que aplicar
            $articles->category;
            $articles->user;
        });

        //dd($articles);

        return view('admin.articles.index')
            ->with('articles', $articles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // nos traemos todas las categorías
        $categories = Category::orderBy('name','ASC')->lists('name', 'id');
        
        $tags = Tag::orderBy('name','ASC')->lists('name', 'id');

        return view('admin.articles.create')->with('categories', $categories)->with('tags', $tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {

        //Manipulación de imágenes
        if($request->file('image'))
        {
            $file = $request->file('image');
            
            $name = 'blogfacilito_' . time() . '.' . $file->getClientOriginalExtension();

            
            $path = public_path() . '/img/articles/';
            
            $file->move($path, $name);
        }

        
        $article = new Article($request->all());
        
        $article->user_id = \Auth::user()->id;
        
        $article->save();

        $article->tags()->sync($request->tags);

        $image = new Image();

        $image->name = $name;

        $image->article()->associate($article);
        
        $image->save();

        Flash::success('Se ha creado el artículo ' . $article->title . ' de forma satisfactoria!');


        return redirect()->route('admin.articles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Article::find($id);
        $article->category; // Esta la obtiene del modelo.
                            // Esta función del modelo nos
                            // trae la relación que tenemos
                            // con las categorias
        //dd($article->category->id);
        $categories = Category::orderBy('name', 'DESC')
                       ->lists('name', 'id');
        $tags = Tag::orderBy('name', 'DESC')
                       ->lists('name', 'id');

        $my_tags = $article->tags->lists('id')->ToArray();
            // si le quitamos la funcion ToArray, nos
            // devolvería un array dentro de un Objeto, y
            // lo que queremos es sólo un Array
        //dd($my_tags);

        return view('admin.articles.edit')
            ->with('categories', $categories)
            ->with('article', $article)
            ->with('tags', $tags)
            ->with('my_tags', $my_tags);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request->tags);
        $article = Article::find($id);
        //dd($article);
        $article->fill($request->all());
        $article->save();

        $article->tags()->sync($request->tags);
        Flash::warning('Se ha editado el artículo ' .
                       $article->title .
                       ' de forma exitosa!');

        return redirect()->route('admin.articles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::find($id);
        $article->delete();

        Flash::error('Se ha borrado el artículo ' .
                     $article->title .
                     ' de forma exitosa!');

        return redirect()->route('admin.articles.index');
    }
}
