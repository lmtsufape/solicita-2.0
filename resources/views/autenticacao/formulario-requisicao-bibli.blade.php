@extends('layouts.app')

@section('conteudo')
    <!-- @section('navbar2.blade.php')
    Home
@endsection -->
    <div class="container">
        <!-- TITULO -->

        <div class="row mx-1 p-0" style="border-bottom: var(--textcolor) 2px solid">
            <div class="col-md-12 tituoRequisicao mt-3 p-0">
                Biblioteca
            </div>
        </div>

        <div class="row my-3">
            <div class="col-md-6">
                <div class="card rounded-4 shadow-sm h-100 overflow-hidden">
                    <div class="card-header bg-white">
                        <h3>
                            Ficha Catalográfica
                        </h3>
                        <span class="text-muted">Gere a ficha catalográfica do seu trabalho de forma automática e imediata, preenchendo as informações necessárias.</span>
                    </div>
                    <div class="card-body text-center d-flex flex-column justify-content-center align-items-center gap-3">
                        <a class="btn btn-lg btn-success w-75 rounded-3" href="{{ route('fichas.create') }}">Gerar ficha</a>
                        <a class="text-success rounded-3" href="{{ route('fichas.index') }}">Ver minhas fichas</a>

                    </div>
                    <div class="alert alert-success mx-3 rounded-4">
                        <strong>Observação:</strong> A ficha catalográfica é gerada automaticamente pelo sistema, com base nas informações fornecidas.
                    </div>
                </div>
            </div>


            <div class="col-md-6">
                <div class="card rounded-4 shadow-sm h-100 overflow-hidden">
                    <div class="card-header bg-white">
                        <h3> Depósito do TCC </h3>
                        <span class="text-muted">Solicitação sujeita a análise da Biblioteca</span>
                        <br>
                        <span class="text-muted">Alunos Concluintes</span>
                    </div>
                    <div class="card-body text-center">
                        <a class="btn btn-lg btn-primary w-75 rounded-3" href="{{ route('create-deposito') }}">Solicitar depósito</a>
                    </div>
                    <div class="alert alert-primary mx-3 rounded-4">
                        <strong>Observação:</strong> Sua solicitação será analisada pela Biblioteca. O andamento pode ser acompanhado em <a class="link link-primary" href="{{ route('listar-requisicoes-aluno') }}"><strong>Requisições</strong></a>.
                    </div>
                </div>
            </div>
        </div>

        <!-- BOTOES -->
        <div class="row justify-content-between my-3">
            <div class="col-md-6">
                <a class="btn w-25" href="{{ route('cancela-requisicao')}}" style="background-color: var(--padrao); border-radius: 0.5rem; color: white; font-size: 17px">
                    {{ ('Voltar') }}
                </a>
            </div>
        </div>

    </div>

@endsection
