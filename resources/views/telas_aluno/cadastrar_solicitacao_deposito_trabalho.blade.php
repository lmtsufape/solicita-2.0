@extends('layouts.app')

@section('conteudo')
    @section('navbar2.blade.php')

    @endsection

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <form method="POST" enctype="multipart/form-data" id="formRequisicao"
                        action="{{ route('criarDeposito') }}">
                    @csrf
                    <input type="hidden" name="tipo_documento" value="Comprovante de Deposito">
                    <input type="hidden" name="perfil_id" value="{{$perfil->id}}">

                    <! –– Solicitacao Comprovante de Deposito ––>

                    <div class="col-md-12 corpoFicha shadow my-4">

                        <div class="row">
                            <div class="col-md-12 cabecalho py-2">
                                <span class="tituloCabecalho">Solicitação de Comprovante de Depósito</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 py-2 textoFicha">
                                <div class="form-group">
                                    <label for="autor_nome">Nome: <span style="color: red">*</span></label>
                                    <input type="text" class="form-control @error('autor_nome') is-invalid @enderror" id="autor_nome" name="autor_nome"
                                           placeholder="Digite o nome do Autor" value="{{$usuario->name}}" required>

                                    @error('autor_nome')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{$message}}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="autor_cpf">CPF: <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="autor_sobrenome" name="autor_cpf"
                                           placeholder="Digite o CPF do Autor" disabled value="{{$aluno->cpf}}" required>
                                </div>
                                <div class="form-group">
                                    <label for="autor_curso">Curso: <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="autor_curso" name="autor_curso"
                                           placeholder="Digite o Curso do Autor" disabled value="{{$curso->nome}}" required>
                                </div>
                                <div class="form-group">
                                    <label for="titulo_tcc">Título do trabalho: <span style="color: red">*</span></label>
                                    <textarea class="editor-ckeditor1 @error('titulo_tcc') is-invalid @enderror" id="titulo_tcc" name="titulo_tcc" required>{{old('titulo_tcc')}}</textarea>

                                    @error('titulo_tcc')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{$message}}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="registro_patente">Registro de patente: <span style="color: red">*</span></label>
                                    <div class="@error('registro_patente') is-invalid @enderror">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="registro_patente" id="registro_patente_sim" value="true" required {{ old('registro_patente') == 'true' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="registro_patente_sim">Sim</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="registro_patente" id="registro_patente_nao" value="false" required {{ old('registro_patente') == 'false' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="registro_patente_nao">Não</label>
                                        </div>
                                    </div>

                                    @error('registro_patente')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{$message}}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="anexo1">Trabalho de Conclusão de Curso (TCC): <span
                                            style="color: red">*</span></label><br>
                                    <input type="file" id="anexo1" accept="application/pdf, .docx, .doc" name="anexo_tcc"
                                            style="margin-bottom: 0px" class="@error('anexo_tcc') is-invalid @enderror" required>

                                    @error('anexo_tcc')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{$message}}</strong>
                                        </span>
                                    @enderror
                                    <br>
                                    <span id="tipoAnexo"
                                            style="font-size: small; color: gray; margin-top: 0px; margin-bottom: 10px">Tipos permitidos: PDF, DOCX e DOC. </span>
                                </div>

                                <div class="form-group">
                                    <label for="anexo_comprovante_autorizacao">Termo de autorização: <span
                                            style="color: red">*</span>
                                    </label><br>
                                    <input type="file" id="anexo_comprovante_autorizacao" accept="application/pdf, .docx, .doc" name="anexo_comprovante_autorizacao"
                                           style="margin-bottom: 0px" class="@error('anexo_comprovante_autorizacao') is-invalid @enderror" required>

                                    @error('anexo_comprovante_autorizacao')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{$message}}</strong>
                                        </span>
                                    @enderror
                                    <br>
                                    <span id="tipoAnexo"
                                          style="font-size: small; color: gray; margin-top: 0px; margin-bottom: 10px">Tipos permitidos: PDF, DOCX e DOC. </span>
                                    <br>
                                    <input type="file" accept="application/pdf, .docx, .doc" name="anexo_publicacao_parcial"
                                           style="margin-bottom: 0px" class="@error('anexo_publicacao_parcial') is-invalid @enderror">

                                    @error('anexo_publicacao_parcial')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{$message}}</strong>
                                        </span>
                                    @enderror
                                    <br>
                                    <span id="tipoAnexo"
                                          style="font-size: small; color: gray; margin-top: 0px; margin-bottom: 10px">
                                          <strong>OBS</strong>: Em caso de Publicação Parcial será necessária uma declaração justificando a necessidade, que deve estar assinada pelo aluno e orientador.
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; text-align: justify; text-justify: inter-word;">
                        <div>
                            <input type="checkbox" id="checkBoxConfirma" name="checkBoxConfirma" onchange="verificaCheckBoxConfirma()">
                        </div>
                        &nbsp
                        <div>
                            <label for="checkBoxConfirma"> {{ __('messages.autorizo')}} <span style="color: red">*</span> </label>
                        </div>
                    </div>

                    <div class="row justify-content-between mt-5">
                        <div class="col-md-4">
                            <a type="button" class="btn btn-block"
                               style="background-color: var(--padrao); border-radius: 0.5rem; color: white;"
                               href="{{ route('prepara-requisicao-bibli') }}">Voltar</a>
                        </div>
                        <div class="col-md-4 text-right">
                            <button type="submit" class="btn btn-block"
                                    id="botaoEnviar"
                                    style="background-color: var(--confirmar); border-radius: 0.5rem; color: white;">
                                Enviar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>

        var sel = $('#produto');
        var selected = sel.val(); // cache selected value, before reordering
        var opts_list = sel.find('option');
        opts_list.sort(function(a, b) { return $(a).text() > $(b).text() ? 1 : -1; });
        sel.html('').append(opts_list);
        sel.val(selected); // set cached selected value

        function verificaCheckBoxConfirma() {
            var checkBoxConfirma = document.getElementById("checkBoxConfirma");
            var botaoEnviar = document.getElementById("botaoEnviar");
            if (checkBoxConfirma.checked == true){
                botaoEnviar.disabled = false;
            } else {
                botaoEnviar.disabled = true;
            }
        }
        verificaCheckBoxConfirma();
    </script>
@endsection
