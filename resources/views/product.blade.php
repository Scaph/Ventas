@extends('layouts.app')

@section('content')
  @if ($alert = session()->get ("alert"))
      <x-alert :type="$alert['type']" :message="$alert['message']"/>
  @endif

  </div>
  <div class="w3-contanier w-50 border p-4 mx-auto">
    <form action="{{ route('products.store') }}" method="post">
      @csrf

      <div class="mb-3">
        <label for="">Categoria</label>
        {!! Form::select('category_id', App\Models\Category::obtenercontactos()) !!}
      </div>

      <div class="mb-3">
        <label for="name" class="form-label">Nombre del producto</label>
        <input type="text" name="name" class="form-control">
      </div>

      <button type="submit" class="btn btn-primary ">Agregar</button>
    </form>
    @foreach ($productos as $producto)
      <div>
        <div>
          <p>
            <center>{{ $producto->name }}</center>
          </p>
        </div>
      </div>
    @endforeach
  </div>
@endsection
