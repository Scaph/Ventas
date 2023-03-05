@extends('layouts.app')

@section('content')
  <div class="container w-100 border p-3">
    <form action="{{ route('sales.store') }}" method="POST">
      @csrf
      <div class="d-grid gap-2 col-4 mx-auto"> 
        @if (session('success'))
          <h6 class="alert alert-success">{{ session('success') }}</h6>
        @endif
        @error('amount')
          <h6 class="alert alert-danger">Favor de poner un valor entero en cantidad
          </h6>
        @enderror
        @error('price')
          <h6 class="alert alert-danger">Favor de poner un valor entero en el precio
          </h6>
        @enderror
      </div>

      <div class="row">
        <div class="col">
          <label for="">Producto</label>
          {!! Form::select('product_id', App\Models\Product::obtenercontactos()) !!}
        </div>
        <div class="col">
          <label for="">Cliente</label>
          {!! Form::select('customer_id', App\Models\Customer::obtenercontactos()) !!}
        </div>
        <div class="col">
          <label for="">Empleado</label>
          {!! Form::select('contact_id', App\Models\Contact::obtenercontactos()) !!}
        </div>
        <div class="col">
          <input type="text" name="amount" class="form-control"
            placeholder="Cantidad">
        </div>
        <div class="col">
          <input type="text" name="price" class="form-control"
            placeholder="Precio">
        </div>
      </div>
      <br>
      <div class="d-grid gap-2 col-4 mx-auto">
        <button type="submit" class="btn btn-primary">Agregar</button>
      </div>
    </form>
  </div>
  </div>
  <br>
  <div class="table-success">
    <div class="panel-heading">
      <center>
        <h3>Ventas</h3>
      </center>
    </div>
    <div class="panel-body">
      <div class="col-md-12" style="padding-top: 2%;">
        <table class="table table-bordered" id="lista_wos">
          <thead>
            <tr>
              <th style="text-align: center;" class="bg-success">ID de venta</th>
              <th style="text-align: center;" class="bg-success">Empleado</th>
              <th style="text-align: center;" class="bg-success">Email empleado
              </th>
              <th style="text-align: center;" class="bg-success">Categoria</th>
              <th style="text-align: center;" class="bg-success">Cliente</th>
              <th style="text-align: center;" class="bg-success">Producto</th>
              <th style="text-align: center;" class="bg-success">Cantidad</th>
              <th style="text-align: center;" class="bg-success">Precio</th>
              <th style="text-align: center;" class="bg-success">Editar</th>
              <th style="text-align: center;" class="bg-success">Eliminar</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($todos as $todo)
              <tr class="">
                <td>
                  <center>{{ $todo->id }}</center>
                </td>
                <td>
                  <center>{{ $todo->contactname }}</center>
                </td>
                <td>
                  <center>{{ $todo->email }}</center>
                </td>
                <td>
                  <center>{{ $todo->categoryname }}</center>
                </td>
                <td>
                  <center>{{ $todo->customername }}</center>
                </td>
                <td>
                  <center>{{ $todo->productname }}</center>
                </td>
                <td>
                  <center>{{ $todo->amount }}</center>
                </td>
                <td>
                  <center>{{ $todo->price }}</center>
                </td>
                <td>
                  <center><a href="{{ route('sales.edit', $todo->id) }}"
                      class="btn btn-warning">Editar</a></center>
                </td>
                <div>
                  <td>
                    <form action="{{ route('sales.destroy', $todo->id) }}"
                      method="POST">
                      @csrf
                      @method('DELETE')
                      <center><button type="submit"
                          class="btn btn-danger">Eliminar</button></center>
                    </form>
                  </td>
                </div>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
