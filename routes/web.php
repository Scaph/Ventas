<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\TodosController;
use App\Models\Contact;
use App\Models\Productos;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
    
*/

#Estas rutas son del inicio del proyecto
#Ruta inicial
Route::get('/', fn () => auth()->check() ? view('welcome') : redirect('/login'));
    // return view('welcome');
Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');




#Rutas de mi primera prueba
// Route::get('/ventas', [TodosController::class, 'index'])->name('todos');
// Route::post('/ventas', [TodosController::class, 'store'])->name('todos');
// Route::patch('/ventas', [TodosController::class, 'store'])->name('todos-edit');
// Route::delete('/ventas', [TodosController::class, 'store'])->name('todos-destroy');
#Rutas de mi aprendizaje

// Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
// Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
// Route::get('/sales/{id}/edit', [SaleController::class, 'edit'])->name('sales.edit');
// Route::put('/sales/{id}', [SaleController::class, 'update'])->name('sales.update');
// Route::delete('/sales/{id}', [SaleController::class, 'destroy'])->name('sales.destroy');
// Hace todas las rutas de arriba por la nomenclatura correcta, te ahorras código
Route::resource('sales', SaleController::class);




// Route::get('/contact', function(){
//     return Response::view('contact');
// });
// Route::post('/contact',function(HttpRequest $request) {
//     $data = $request->all();
//     // dd($data);
//     // $contact = new Contact();
//     // $contact->name = $data["name"];
//     // $contact->phone_number  = $data["phone_number"];
//     // $contact->save();
//     Contact::create($data);
    
//     return "Añadido";
// });

// Route::get('/home',[ContactController::class, 'index'])->name('home');





#Rutas de mi proyecto prueba DBG
// Route::get('/ventas', [TodosController::class, 'index'])->name('todos');
// Route::post('/ventas', [TodosController::class, 'store'])->name('todos');
// Route::patch('/tareas', [TodosController::class, 'store'])->name('todos-edit');
// Route::delete('/tareas', [TodosController::class, 'store'])->name('todos-destroy');

Route::get('/contacts/create',[ContactController::class, 'create'])->name('contacts.create');
Route::post('/contacts',[ContactController::class, 'store'])->name('contacts.store');


Route::get('/products',[ProductController::class,'create'])->name('products.create');
Route::post('/products',[ProductController::class,'store'])->name('products.store');