@extends('layouts.app')

@section('conteudo')
<!-- Informações do aluno -->

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 corpoFicha shadow">
            <div class="row cabecalho">
                <div class="col-md-12 py-2 tituloCabecalho">
                    <div class="row justify-content-between  align-middle">
                        <div class="col-md-6">
                            Suas informações
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{route('alterar-senha')}}" class="btn p-0">
                                <img src="images/alterar_senha.svg" height="35px"
                                     title="Alterar Senha"></a>
                            <a href="{{route('editar-info')}}" class="btn p-0">
                                <img src="images/botao_editar.svg" height="35px"
                                     title="Editar Perfil"></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <label for="nome" class="textoFicha mb-1">Nome:</label>
                    <div class="mt-0 pt-0"
                         style="color: var(--textcolor); font-weight: 500; font-size: 18px">{{Auth::user()->name}}</div>
                </div>
            </div>
            <div class="row justify-content-between mt-2">
                <div class="col-md-6">
                    <label for="nome" class="textoFicha mb-1">CPF:</label>
                    <div id="cpf" class="mt-0 pt-0"
                         style="color: var(--textcolor); font-weight: 500; font-size: 18px">{{$aluno->cpf}}</div>
                </div>
                <div class="col-md-6">
                    <label for="biblioteca" class="textoFicha mb-1">Vínculo:</label>
                    <div class="mt-0 pt-0"
                         style="color: var(--textcolor); font-weight: 500; font-size: 18px">{{$perfil->situacao}}</div>
                </div>
            </div>
            <div class="row mt-2 mb-3">
                <div class="col-md-12">
                    <label for="nome" class="textoFicha mb-1">Instituição:</label>
                    <div class="mt-0 pt-0"
                         style="color: var(--textcolor); font-weight: 500; font-size: 18px">{{$unidadeAluno}}</div>
                </div>
            </div>
            <div class="row mt-2 mb-3">
                <div class="col-md-6">
                    <label for="nome" class="textoFicha mb-1">Curso:</label>
                    <div class="mt-0 pt-0"
                         style="color: var(--textcolor); font-weight: 500; font-size: 18px">{{$cursoAluno->nome}}</div>
                </div>
                <div class="col-md-6">
                    <label for="nome" class="textoFicha mb-1">E-mail:</label>
                    <div class="mt-0 pt-0"
                         style="color: var(--textcolor); font-weight: 500; font-size: 18px">{{$user->email}}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 corpoFicha shadow">
            <div class="row cabecalho">
                <div class="col-md-12 py-2 tituloCabecalho">
                    <div class="row justify-content-between  align-middle">
                        <div class="col-md-6">
                            Perfis
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="#" class="btn p-0" onclick="event.preventDefault(); definirPadrao()" title="Definir como padrão">
                                <img src="images/home-default.svg" height="35px">
                            </a>
                            <a href="{{route('adiciona-perfil')}}" class="btn p-0">
                                <img src="images/botao_add.svg"  style="border: white 3px solid; border-radius: 0.5rem" height="35px"
                                     title="Adicionar Perfil"></a>
                            <a href="#" class="btn p-0" onclick="event.preventDefault(); excluirPerfil()">
                                <img src="images/botao_remover.svg" height="35px"
                                     title="Excluir Perfil">
                            </a>

                        </div>
                    </div>
                </div>
            </div>

            <div class="my-3 textoFicha">
                @foreach($perfisAluno as $pa)
                    <form method="POST" enctype="multipart/form-data" id="formExcluirPerfil"
                          action="{{ route('excluir-perfil') }}">
                        @csrf
                        <input type="radio" name="idPerfil" value="{{$pa->id}}" id="radioButton"><span
                            style="margin-left:10px">{{$pa->default}}</span> - {{$pa->situacao}}</input>
                        @if($pa->valor==false)
                            <a id="botao" data-toggle="modal" data-target="#myModal" aria-hidden="true"
                               data-whatever="{{$pa->default}}" onclick="perfilId({{$pa->id}})">
                    <span class="glyphicon glyphicon-ok-sign" style="overflow: hidden; color:gray"
                          data-toggle="tooltip" data-placement="top"
                          title="Definir como padrão."
                          data-id="{{$pa->id}}"
                          data-nome="{{$pa->default}}"
                          data-title="{{$pa->id}}">
                    </span>
                            </a>
                        @endif
                        @if($pa->valor==true)
                            <span class="glyphicon glyphicon-ok-sign" style="overflow: hidden; color:green"
                                  data-toggle="tooltip" data-placement="top"
                                  title="Perfil padrão.">
                </span>
                        @endif
                        <br>
                @endforeach
                    </form>
                <form method="POST" id="formDefinirPadrao" action="{{ route('perfil-padrao') }}">
                    @csrf
                    <input type="hidden" name="idPerfil" id="idPerfilPadrao">
                </form>
            </div>
            <div>
                @foreach($perfisAluno as $pa)
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                         aria-hidden="true">
                        <form method="post" id="formModal" action="{{ route("perfil-padrao") }}">
                            @csrf
                            <input type="hidden" name="idPerfil" value="" id="id_documento">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <label>Definir o perfil como padrão?</label>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-footer">
                                        <a type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right:10px">
                                            {{ ('Não') }}
                                        </a>
                                        <a type="button" class="btn btn-primary btn-primary-lmts" onclick="event.preventDefault(); confirma()"
                                           href="{{ route("perfil-padrao")}}" style="margin-right:10px">
                                            {{ ('Sim') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                    </div>
                    </form>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>

function definirPadrao(){
    var selecionado = document.querySelector('input[name="idPerfil"]:checked');
    if(!selecionado){
        alert('Selecione um perfil primeiro.');
        return;
    }
    document.getElementById('idPerfilPadrao').value = selecionado.value;
    document.getElementById('formDefinirPadrao').submit();
}

function excluirPerfil(){
    var selecionado = document.querySelector('input[name="idPerfil"]:checked');
    if(!selecionado){
        alert('Selecione um perfil primeiro.');
        return;
    }
    var confirma = confirm("Você tem certeza que deseja excluir este perfil?");
    if(confirma){
        selecionado.closest('form').submit();
    }
}
function confirma(){
    document.getElementById("formModal").submit();
}
</script>
<script>
function perfilId(id){
  document.getElementById('id_documento').value = id;
}
</script>

<script>
    var cpf = document.getElementById('cpf').innerText;
    document.getElementById('cpf').innerText = cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
</script>



@endsection
