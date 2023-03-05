@extends('layouts.app')

@section('content')
  <div class="panel-heading">
    <center>
      <h3>Editar la venta # {{ $sale->id }}</h3>
    </center>
  </div>
  <div class="container w-100 border p-3">
    <form action="{{ route('sales.update', $sale->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="row">
        <div class="col">
          <label for="">Producto</label>
          {!! Form::select('product_id', App\Models\Product::obtenercontactos(),$sale->product_id) !!}
        </div>
        <div class="col">
          <label for="">Cliente</label>
          {!! Form::select('customer_id', App\Models\Customer::obtenercontactos(),$sale->customer_id) !!}
        </div>
        <div class="col">
          <label for="">Empleado</label>
          {!! Form::select('contact_id', App\Models\Contact::obtenercontactos(),$sale->contact_id) !!}
        </div>
        <div class="col">
          <input type="text" value= "{{ $sale->amount }}" name="amount" class="form-control"
            placeholder="Cantidad">
        </div>
        <div class="col">
          <input type="text" value= "{{ $sale->price }}" name="price" class="form-control"
            placeholder="Precio">
        </div>
      </div>
      <br>
      <div class="d-grid gap-2 col-4 mx-auto">
        <button type="submit" class="btn btn-warning">Actualizar</button>
      </div>
  </div>

  </form>
  </div>
@endsection
