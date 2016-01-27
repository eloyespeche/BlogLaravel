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
        $categories = Category::orderBy('name','ASC')
            ->lists('name', 'id');
                    // (se empleará en el select de la
                    // vista)
                    // lists() va a mostrar sólo las
                    // columnas 'name' e 'id'
        // obtenemos todos los tags
        $tags = Tag::orderBy('name','ASC')
            ->lists('name', 'id');

        return view('admin.articles.create')
            ->with('categories', $categories)
            ->with('tags', $tags);

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
            //dd($file);
                /*
                    Visualiza:
                    UploadedFile {#29 ▼
                      -test: false
                      -originalName: "a.png"
                      -mimeType: "image/png"
                      -size: 2381
                      -error: 0

                 */

            // Esto lo utilizamos por si se mandan 2 ficheros
            // con el mismo nombre. Para evitar la colisión.
            $name = 'blogfacilito_' .
                    time() .
                    '.' .
                    $file->getClientOriginalExtension();

            //dd($name);
                /*
                    Visualiza:
                    "blogfacilito_1448476889.png"
                    (si actualizamos la página, obtenemos otro
                    nombre único)
                    "blogfacilito_1448476986.png"
                 */
            $path = public_path() . '/img/articles/';
            //dd($path);
                /*
                    Visualiza:
                    "C:\xampp\htdocs\CodigoFacilitoLaravel5\
                    Projects\blog\public/images/articles/"
                 */

            $file->move($path, $name);
        }


        $article = new Article($request->all());
        // para obtener el usuario autentificado
        $article->user_id = \Auth::user()->id;
        //dd($article);
                /*
                    Visualiza:
                      ....
                      #attributes: array:3 [▼
                        "title" => "título"
                        "category_id" => "1"
                        "content" => "contenido"
                      ]
                      ....
                 */
        //dd(\Auth::user()->id);
                /*
                    Visualiza: 1
                 */
        $article->save();

        $article->tags()->sync($request->tags);
                // sync lo que hace es rellenar la tabla
                // pivote


        $image = new Image();
        $image->name = $name;
        // Si varias personas están creando un artículo a la
        // misma vez, podríamos tener un problema, ya que
        // podríamos almacenar el id de un artículo incorrecto.
        // Para evitarlo:
        $image->article()->associate($article);
                // associate() lo que va a hacer es pasar
                // como parámetro el objeto $article, y va
                // a tomar que es lo que lo asocia, en
                // este caso lo que asocia a las imágenes
                // y los artículos sería la llave foránea
                // 'article_id'

        $image->save();

        Flash::success('Se ha creado el artículo ' .
                       $article->title .
                       ' de forma satisfactoria!');


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
