@extends('layouts.app')

@section('content')
  <div class="container pt-4 p-3">
    <div class="text-center">
        <h3>Bienvenido</h3>
        <h4>Empleados registrados para ventas:</h4>
    </div>
    <div class="row">

      @forelse ($contacts as $contact)
        <div class="col-md-4 mb-3">
          <div class="card text-center">
            <div class="card-body">
              <h3 class="card-title text-capitalize">{{ $contact->name }}</h3>
              <p>Email: <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></p>

            </div>
          </div>
        </div>
      @empty
        <div class="col-md-4 mx-auto">
          <div class="card card-body text-center">
            <p>¡Aún no hay empleados de alta!</p>
            <a href="{{ route('contacts.create') }}">Agregar</a>
          </div>
        </div>
      @endforelse
    </div>
  </div>
@endsection