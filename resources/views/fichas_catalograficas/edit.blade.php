@extends('layouts.app')

@section('conteudo')
@section('navbar2.blade.php')

@endsection

<div class="container">
    <div class="row mt-5 tituloFicha">
        <div class="col-md-12">
            Ficha Catalográfica
        </div>
    </div>
    <form method="POST" enctype="multipart/form-data" id="formRequisicao"
            action="{{ route('fichas.update', ['ficha_catalografica_id' => $ficha->id]) }}">
        @csrf
        @method('PUT')
        @include('fichas_catalograficas._form', ['ficha' => $ficha])

        <div class="row justify-content-between mt-5">
            <div class="col-md-4">
                <a type="button" class="btn btn-block"
                    style="background-color: var(--padrao); border-radius: 0.5rem; color: white;"
                    href="{{ route('prepara-requisicao-bibli') }}">Voltar</a>
            </div>
            <div class="col-md-4 text-right">
                <button type="submit" class="btn btn-block" id="botaoEnviar"
                        style="background-color: var(--confirmar); border-radius: 0.5rem; color: white;"
                        >
                    Enviar
                </button>
            </div>
        </div>
    </form>

</div>


@endsection
