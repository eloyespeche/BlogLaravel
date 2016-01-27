<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // por defecto viene false, lo cambiamos a true
                     // porque queremos usarlo...
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /*
            El arreglo que devuelve, es donde vamos a definir las
            reglas
         */
        return [
            'name'     => 'min:4|max:120|required',
            'email'    => 'min:4|max:250|required|unique:users',
            'password' => 'min:8|max:32|required'
        ];
    }
}
