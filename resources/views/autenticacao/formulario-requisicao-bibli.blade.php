@extends('layouts.app')

@section('conteudo')
    <!-- @section('navbar2.blade.php')
    Home
@endsection -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 corpoRequisicao shadow">

            <!-- TITULO -->

            <div class="row mx-1 p-0" style="border-bottom: var(--textcolor) 2px solid">
                <div class="col-md-12 tituoRequisicao mt-3 p-0">
                    Biblioteca
                </div>
            </div>

            <!-- OPÇOES -->

            <div class="row py-2">
                <div class="col-md-12">
                    <form method="POST" enctype="multipart/form-data" id="formRequisicao"
                          action="{{ route('cadastrarDocumento') }}">
                        @csrf
                        <div class="py-3">
                            <label class="textoFicha">Aluno(a):</label>
                            <input class="form-control" type="text" name="nome" size="100%" disabled value="{{Auth::user()->name}}">
                        </div>
                        <div class="py-3">
                            <label class="textoFicha">Perfil:</label>
                            <select name="default" class="form-control" style="background-color: var(--background)">
                                @foreach($perfis as $perfil)
                                    <option @if($perfil->valor==true) selected
                                            @endif value="{{$perfil->id}}">{{$perfil->default}}
                                        - {{$perfil->situacao}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="py-3 ">
                            <label class="textoFicha">Ficha catalográfica:</label>
                            @foreach($tipos_documentos as $tipo)
                                <div>
                                    <input type="radio" name="documento" value="{{$tipo->id}}"
                                           id="{{$tipo->tipo}}" @if($tipo->tipo == 'Dissertacao') checked @endif>
                                    @if($tipo->tipo == 'Monografia')
                                        <label for="{{$tipo->tipo}}">Monografia</label>
                                    @elseif($tipo->tipo == 'Tese')
                                        <label for="{{$tipo->tipo}}">Tese</label>
                                    @elseif($tipo->tipo == 'TCC')
                                        <label for="{{$tipo->tipo}}">Trabalho de Conclusão de Curso</label>
                                    @elseif($tipo->tipo == 'ProgramaEduc')
                                        <label for="{{$tipo->tipo}}">Produto Educacional</label>
                                    @elseif($tipo->tipo == 'Dissertacao')
                                        <label for="{{$tipo->tipo}}">Dissertação</label>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="pt-1 pb-3">
                            <label class="textoFicha">Outros documentos:</label>
                            {{--
                            <div>
                                <input type="radio" name="documento" value="comprovante" id="ComprovanteNadaConsta">
                                <label for="ComprovanteNadaConsta">Alunos não Concluintes - Comprovante de Nada Consta</label>
                            </div>
                            --}}
                            <div>
                                <input type="radio" name="documento" value="ComprovanteDepositoTrabalhoConclusao" id="ComprovanteDepositoTrabalhoConclusao">
                                <label for="ComprovanteDepositoTrabalhoConclusao">Alunos Concluintes - Depósito do Trabalho de Conclusão de Curso e Nada Consta na Biblioteca</label>
                            </div>
                        </div>

                        <!-- BOTOES -->
                        <div class="row justify-content-between my-3">
                            <div class="col-md-6">
                                <a class="btn" href="{{ route('cancela-requisicao')}}" style="background-color: var(--padrao); border-radius: 0.5rem; color: white; font-size: 17px">
                                    {{ ('Cancelar') }}
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="submit" class="btn" style="background-color: var(--confirmar); border-radius: 0.5rem; color: white; font-size: 17px">
                                    {{ ('Solicitar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection
