<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Image;

class ImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // obtenemos todas las imágenes
        // si no añadimos arriba: 'use App\Image', tendríamos
        // que hacerlo así: $images = \App\Image::all();
        // Pero es más cómodo hacerlo de esta manera (añadiendo
        // arriba la llamada)
        $images = Image::all();
        //dd($images);
        // Para mostrar el título del artículo
        // (para ello usamos el modelo de las imágenes)
        $images->each(function($images){
            $images->article;
        });

        return view('admin.images.index')
               ->with('images', $images);
    }


}
