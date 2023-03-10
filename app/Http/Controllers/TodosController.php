<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodosController extends Controller
{
    // index para mostrar todos los todos
    // store para guardar un todo
    // update para actualizar un todo
    // destroy para eliminar un todo
    // edit para mostrar el formulario de edición
    public function store(Request $request){
        $request->validate([
            'title' => 'required|min:3'
        ]);
        $todo = new Todo;
        $todo -> title = $request->title;
        $todo ->save();

        return redirect()->route('todos')-> with('success','Cliente agregado correctamente');

    }
    public function index(){
        $todos = Todo::all();
        //dd($todos);
        return view('index', ['todos'=> $todos]);
    }
}
