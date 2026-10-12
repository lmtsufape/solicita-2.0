@extends('layouts.app')

@section('conteudo')
    <!-- @section('navbar2.blade.php')
    Home
@endsection -->

<style>
    hr {
        border: none;
        height: 2px;
        /* Set the hr color */
        color: #333; /* old IE */
        background-color: #1B2E4F; /* Modern Browsers */
        margin-top: 0px;
    }

    .center {
        margin: auto;
        width: 50%;
        padding: 10px;
    }

    h3 {
        font-size: 22px;
        margin-bottom: 5px;
    }

    .form-control {
        width: 97%;
    }
</style>

<div class="container">

    <!--TITULO-->

    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="row tituloFicha">
                <div class="col-md-12">
                    Indeferir Ficha
                </div>
            </div>

            <form method="POST" enctype="multipart/form-data" id="formRequisicao"
                  action="{{ route('atualizar-rejeicao',$requisicao->id) }}">
                @csrf

                <div class="col-md-12 corpoFicha shadow mt-4">
                    <div class="row cabecalho tituloCabecalho">
                        <div class="col-md-12">
                            Dados da ficha
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 textoFicha py-2 px-0">
                            <div class="form-group ml-3">
                                <label for="exampleFormControlInput1">Nome do solicitante:</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       placeholder="" value="{{$usuario->name}}" disabled>
                            </div>
                            <div class="form-group ml-3">
                                <label for="exampleFormControlInput1">Email do solicitante:</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       placeholder="" value="{{$usuario->email}}" disabled>
                            </div>
                            <div class="form-group ml-3">
                                <label for="exampleFormControlInput1">Autor do documento:</label>
                                <input type="text" class="form-control" id="autor_nome" name="autor_nome"
                                       placeholder="" value="{{$ficha->autor_nome}}" disabled>
                            </div>
                            <div class="form-group ml-3">
                                <label for="exampleFormControlInput1">Titulo do documento:</label>
                                <input type="text" class="form-control" id="titulo" name="titulo"
                                       placeholder="Mensagem explicativa" value="{{$ficha->titulo}}" disabled>
                            </div>
                            <div class="form-group ml-3">
                                <label for="exampleFormControlInput1">Tipo do documento:</label>
                                <input type="text" class="form-control" id="titulo" name="titulo"
                                       placeholder="Mensagem explicativa"
                                       value="{{\App\Models\TipoFichaCatalografica::find($ficha->tipo_ficha_catalografica_id)->first()->nome}}"
                                       disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 corpoFicha shadow mt-4">
                    <div class="row cabecalho tituloCabecalho">
                        <div class="col-md-12">
                            Motivo
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 textoFicha py-2 p-0">
                            <div class="form-group ml-3">
                                <label for="exampleFormControlInput1">Explicação<span
                                        style="color: red">*</span>:</label>
                                <textarea type="text" class="form-control" id="mensagem" name="mensagem"
                                          placeholder="Motivo da rejeição da ficha" value="" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-between mt-4">
                    <div class="col-md-6">
                        <a type="button" class="btn btn-secondary" style="background-color: var(--padrao); border-radius: 0.5rem; color: white;"
                           href="{{ route('editar-ficha', $requisicao->id) }}">Voltar</a>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-danger" style="background-color: var(--destaque); border-radius: 0.5rem; color: white;">Rejeitar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@php
@endphp
@endsection
