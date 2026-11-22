<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductoRequest;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        // Criterios de orden permitidos
        $ordenes = [
            'nombre_asc' => ['nombre', 'asc'],
            'nombre_desc' => ['nombre', 'desc'],
            'precio_desc' => ['precio', 'desc'],
            'precio_asc' => ['precio', 'asc'],
            'existencias_desc' => ['existencias', 'desc'],
            'existencias_asc' => ['existencias', 'asc'],
            'fecha_desc' => ['created_at', 'desc'],
            'fecha_asc' => ['created_at', 'asc'],
        ];

        $orden = $request->get('orden', 'fecha_desc');
        if (!isset($ordenes[$orden])) {
            $orden = 'fecha_desc';
        }

        // cantidad por pagina: solo 10, 15, 25 o 50
        $porPagina = (int) $request->get('por_pagina', 15);
        if (!in_array($porPagina, [10, 15, 25, 50])) {
            $porPagina = 15;
        }

        // with('categoria') trae las categorias en una sola consulta extra
        $consulta = Producto::with('categoria');

        // cada filtro solo se aplica si el usuario lo lleno
        if ($request->filled('buscar')) {
            $consulta->where('nombre', 'like', '%' . $request->buscar . '%');
        }
        if ($request->filled('categoria')) {
            $consulta->where('categoria_id', $request->categoria);
        }
        if ($request->filled('precio_min')) {
            $consulta->where('precio', '>=', $request->precio_min);
        }
        if ($request->filled('precio_max')) {
            $consulta->where('precio', '<=', $request->precio_max);
        }
        if ($request->filled('estado')) {
            $consulta->where('estado', $request->estado);
        }

        $consulta->orderBy($ordenes[$orden][0], $ordenes[$orden][1]);

        // withQueryString conserva los filtros y el orden al cambiar de pagina
        $productos = $consulta->paginate($porPagina)->withQueryString();

        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.index', compact('productos', 'categorias', 'orden', 'porPagina'));
    }

    public function crear()
    {
        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.crear', compact('categorias'));
    }

    public function store(ProductoRequest $request)
    {
        Producto::create($request->validated());

        return redirect()->route('productos.index')->with('exito', 'Producto registrado.');
    }

    public function editar(Producto $producto)
    {
        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.editar', compact('producto', 'categorias'));
    }

    public function update(ProductoRequest $request, Producto $producto)
    {
        $producto->update($request->validated());

        return redirect()->route('productos.index')->with('exito', 'Producto actualizado.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('productos.index')->with('exito', 'Producto eliminado.');
    }
}