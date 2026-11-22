<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductoRequest extends FormRequest
{
    // La autorización se hace con el middleware de las rutas
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Al editar, el nombre puede ser el mismo del producto actual
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('productos', 'nombre')->ignore($this->route('producto')),
            ],
            'descripcion' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'precio' => 'required|numeric|gt:0',
            'existencias' => 'required|integer|min:0',
            'estado' => 'required|in:disponible,agotado',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique' => 'Ya existe un producto con ese nombre.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'categoria_id.required' => 'Selecciona una categoría.',
            'categoria_id.exists' => 'La categoría no es válida.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número.',
            'precio.gt' => 'El precio debe ser mayor que cero.',
            'existencias.required' => 'Las existencias son obligatorias.',
            'existencias.integer' => 'Las existencias deben ser un número entero.',
            'existencias.min' => 'Las existencias no pueden ser negativas.',
            'estado.required' => 'Selecciona un estado.',
            'estado.in' => 'El estado no es válido.',
        ];
    }
}