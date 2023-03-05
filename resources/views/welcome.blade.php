@extends( 'layouts.app')

@push('scripts')
  <script src="{{ asset('js/welcome.js') }}" defer></script>
@endpush

@section('content')
  <div class="welcome d-flex align-items-center justify-content-center">
    <div class="text-center">
      <h1>Empleados</h1>
      <a class="btn btn-lg btn-dark" href="{{ route ('contacts.create')}}">Iniciar</a>
    </div>
  </div>
  <br>
  <br>

  <div class="welcome d-flex align-items-center justify-content-center">
    <div class="text-center">
      <h1>Agregar producto</h1>
      <a class="btn btn-lg btn-dark" href="{{ route ('products.create')}}">Iniciar</a>
    </div>
  </div>
  <br>
  <br>

  <div class="welcome d-flex align-items-center justify-content-center">
    <div class="text-center">
      <h1>Programa de ventas</h1>
      <a class="btn btn-lg btn-dark" href="{{ route ('sales.index')}}">Iniciar</a>
    </div>
  </div>
@endsection
