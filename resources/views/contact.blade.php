@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Agregar nuevo empleado</div>
          <div class="card-body">
            <form method="POST" action="{{ route('contacts.store') }}">
              @csrf

              <div class="row mb-3">
                <label for="name"
                  class="col-md-4 col-form-label text-md-end">Nombre</label>
                <div class="col-md-6">
                  <input id="name" type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    name="name" autofocus>
                  @error('name')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <div class="row mb-3">
                <label for="phone_number"
                  class="col-md-4 col-form-label text-md-end">Número de teléfono</label>
                <div class="col-md-6">
                  <input id="phone_number" type="tel"
                    class="@error('phone_number') is-invalid @enderror form-control"
                    name="phone_number">
                  @error('phone_number')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <div class="row mb-3">
                <label for="email"
                  class="col-md-4 col-form-label text-md-end">Email</label>
                <div class="col-md-6">
                  <input value="{{ old('email') }}" id="email" type="text"
                    class="@error('email') is-invalid @enderror form-control"
                    name="email">
                  @error('email')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>


              <div class="row mb-3">
                <label for="age"
                  class="col-md-4 col-form-label text-md-end">Edad</label>
                <div class="col-md-6">
                  <input id="age" type="tel"
                    class="@error('age') is-invalid @enderror form-control"
                    name="age">
                  @error('age')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>

              <div class="row mb-0">
                <div class="col-md-6 offset-md-4">
                  <button type="submit" class="btn btn-primary">
                    Enviar
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
