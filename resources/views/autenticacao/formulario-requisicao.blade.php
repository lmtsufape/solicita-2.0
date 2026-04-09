@extends('layouts.app')

@section('conteudo')
<!-- @section('navbar2.blade.php')
    Home
@endsection -->

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 corpoRequisicao shadow">

            <!--TITULO-->
            <div class="row mx-1 p-0" style="border-bottom: var(--textcolor) 2px solid">
                <div class="col-md-12 tituoRequisicao mt-3 p-0">
                    DRCA
                </div>
            </div>

            <!--CORPO-->
            <div class="row py-2">
                <div class="col-md-12">
                    <form method="POST" enctype="multipart/form-data" id="formRequisicao"
                          action="{{ route('confirmacao-requisicao') }}">
                        @csrf
                        <div class="py-3">
                            <label class="textoFicha">Aluno(a):</label>
                            <input class="form-control" type="text" name="nome" size="100%" disabled
                                   value="{{Auth::user()->name}}">
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

                        <div class="py-3">
                            <label class="textoFicha">Tipo de Documento:</label>
                            <div>
                                <input type="checkbox" name="declaracaoVinculo" value="Declaracao de Vinculo"
                                       id="declaracaoVinculo"> Declaração de vínculo.</input></br>

                            </div>
                            <div>
                                <input type="checkbox" name="comprovanteMatricula" value="Comprovante de Matricula"
                                       id="comprovanteMatricula"> Comprovante de matrícula.</input></br>
                            </div>
                            <div>
                                <input type="checkbox" name="historico" value="Historico" id="historico"> Histórico
                                Escolar.</input></br>
                            </div>
                            <div>
                                <input type="checkbox" name="desbloqueioSiga" value="Desbloqueio do Siga" id="desbloqueioSiga"> Desbloqueio do Siga.</input></br>
                            </div>

                            <div>
                                <input type="checkbox" name="programaDisciplina" value="Programa de Disciplina"
                                       id="programaDisciplina"
                                       onclick="checaSelecaoProgramaDisciplina()"> Programa de Disciplina (informar abaixo o nome da disciplina e a finalidade).</input>
                                </br>
                                <textarea maxlength="255"
                                          class="form-control @error('programaDisciplina') is-invalid @enderror @error('requisicaoPrograma') is-invalid @enderror"
                                          form="formRequisicao" style="display:none; margin-top:10px;"
                                          name="requisicaoPrograma" cols="115" id="textareaProgramaDisciplina"
                                          required autocomplete="programaDisciplina"
                                          placeholder="Preencha este campo com o nome da(s) disciplina(s) e a finalidade da requisição."></textarea>
                                @error('programaDisciplina')
                                <span>
                                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                </span>
                                @enderror
                                @error('requisicaoPrograma')
                                <span>
                                    <span class="invalid-feedback" role="alert" style="overflow: visible; display:block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                </span>
                                @enderror
                            </div>

                            <div>
                                <input type="checkbox" name="outros" value="Outros" id="outros"
                                       onclick="checaSelecaoOutros()"> Outros (informar abaixo).<br>
                                </input>
                                <textarea maxlength="255"
                                          class="form-control @error('requisicaoOutros') is-invalid @enderror"
                                          form="formRequisicao" style="display:none; margin-top:10px"
                                          name="requisicaoOutros" cols="115" id="textareaOutrosDocumentos"
                                          required placeholder="O campo deve ser preenchido"></textarea>
                                @error('requisicaoOutros')
                                <span>
                                    <span class="invalid-feedback" role="alert"
                                          style="overflow: visible; display:block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                  </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row justify-content-between my-3">
                            <div class="col-md-6">
                                <a class="btn btn-secondary" href="{{ route('cancela-requisicao')}}" style="background-color: var(--padrao); border-radius: 0.5rem; color: white; font-size: 17px">
                                    {{ ('Cancelar') }}
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <a class="btn btn-primary-lmts"
                                   onclick="event.preventDefault(); validaCampos();"
                                   href="{{ route('confirmacao-requisicao') }}" style="background-color: var(--confirmar); border-radius: 0.5rem; color: white; font-size: 17px">
                                    {{ ('Solicitar') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function checaSelecaoProgramaDisciplina() {
  var checkBoxPrograma = document.getElementById("programaDisciplina");
  var textareaProgramaDisciplina = document.getElementById("textareaProgramaDisciplina");
  if (checkBoxPrograma.checked == true){
    textareaProgramaDisciplina.style.display = "block";
    } else {
    textareaProgramaDisciplina.style.display = "none";
  }
}
function checaSelecaoOutros() {
  var checkBoxOutros = document.getElementById("outros");
  var textareaOutrosDocumentos = document.getElementById("textareaOutrosDocumentos");
  if (checkBoxOutros.checked == true){
    textareaOutrosDocumentos.style.display = "block";
  } else {
    textareaOutrosDocumentos.style.display = "none";
  }
}
function validaCampos() {
  var checkBoxDeclaracao = document.getElementById('declaracaoVinculo');
  var checkBoxComprovante = document.getElementById('comprovanteMatricula');
  var checkBoxHistorico = document.getElementById('historico');
  var checkBoxDesbloqueioSiga = document.getElementById('desbloqueioSiga');
  var checkBoxPrograma = document.getElementById('programaDisciplina');
  var checkBoxOutros = document.getElementById('outros');
  var textareaProgramaDisciplina = document.getElementById("textareaProgramaDisciplina");
  var textareaOutrosDocumentos = document.getElementById("textareaOutrosDocumentos");
  if (checkBoxDeclaracao.checked == false
      && checkBoxComprovante.checked == false
      && checkBoxHistorico.checked == false
      && checkBoxPrograma.checked == false
      && checkBoxOutros.checked == false
      && checkBoxDesbloqueioSiga.checked == false) {
      alert('Informe ao menos um dos documento que deseja solicitar.');
      return false;
  }
  if(checkBoxPrograma.checked==true && document.getElementById("textareaProgramaDisciplina").value==""){
    document.getElementById("textareaProgramaDisciplina").style.border = "2px solid red";
    if(checkBoxOutros.checked==true && document.getElementById("textareaOutrosDocumentos").value==""){
      document.getElementById("textareaOutrosDocumentos").style.border = "2px solid red";
    }
    alert('Os campos devem ser preenchidos corretamente.');
    return false;
  }
  if(checkBoxOutros.checked==true && document.getElementById("textareaOutrosDocumentos").value==""){
    document.getElementById("textareaOutrosDocumentos").style.border = "2px solid red";
    if(checkBoxPrograma.checked==true && document.getElementById("textareaProgramaDisciplina").value==""){
      document.getElementById("textareaProgramaDisciplina").style.border = "2px solid red";
    }
    alert('Os campos devem ser preenchidos corretamente.');
    return false;
  }

  else{
    document.getElementById('formRequisicao').submit();
  }
  return true;
  }
</script>
<!-- <script>
function informacao()
{
var myAlert = document.getElementById("info");
alert('Para o atendimento de sua solicitação, favor informar a(s) disciplina(s) e a finalizade da requisição.');
}
</script> -->
@endsection
