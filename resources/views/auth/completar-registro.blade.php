@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Completar Registro') }}</div>

                <div class="card-body">
                    <div class="alert alert-success">
                        ¡Registro inicial completado con éxito! Aquí podrás completar tu información adicional.
                    </div>

                    <form method="POST" action="{{ route('completar.registro.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Continuar') }}
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