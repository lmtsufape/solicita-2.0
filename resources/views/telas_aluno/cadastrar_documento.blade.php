@extends('layouts.app')

@section('conteudo')
@section('navbar2.blade.php')

@endsection

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="row mt-5 tituloFicha">
                <div class="col-md-12">
                    Ficha Catalográfica -
                    @if($tipo_documento == 2)Monografia
                    @elseif($tipo_documento == 1)Dissertação
                    @elseif($tipo_documento == 3)Produto Educacional
                    @elseif($tipo_documento == 4)Tese
                    @endif
                </div>
            </div>
            <form method="POST" enctype="multipart/form-data" id="formRequisicao"
                  action="{{ route('criarDocumentoBibli') }}">
                @csrf
                <input type="hidden" name="tipo_documento" value="{{$tipo_documento}}">

                <! –– Dados Pessoais ––>

                <div class="col-md-12 corpoFicha shadow my-4">
                    <div class="row">
                        <div class="col-md-12 cabecalho py-2">
                            <span class="tituloCabecalho">Dados Pessoais</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group py-2">
                                <label class="textoFicha" for="exampleFormControlInput1">Nome<span
                                        style="color: red">*</span>:</label>
                                <input type="text" class="form-control" id="nome" name="nome"
                                       placeholder="Nome" value="{{\Illuminate\Support\Facades\Auth::user()->name}}"
                                       disabled>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id_perfil" value="{{ $id_perfil }}">
                </div>

                <! –– Dados do Trabalho ––>

                <div class="col-md-12 corpoFicha shadow my-4">

                    <div class="row">
                        <div class="col-md-12 cabecalho py-2">
                            <span class="tituloCabecalho">Dados do Trabalho</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 py-2">
                            <div class="form-group">
                                <label for="autor_nome">Nome: <span style="color: red">*</span></label>
                                <input type="text" class="form-control" id="autor_nome" name="autor_nome"
                                       placeholder="Digite o nome do Autor" value="" required>
                            </div>
                            <div class="form-group">
                                <label for="autor_sobrenome">Sobrenome: <span style="color: red">*</span></label>
                                <input type="text" class="form-control" id="autor_sobrenome" name="autor_sobrenome"
                                       placeholder="Digite o sobrenome do Autor" value="" required>
                            </div>
                            <div class="">
                                <label for="titulo">Título: <span style="color: red">*</span></label>
                                <div class="mb-2">
                                    <textarea class="editor-ckeditor1" id="titulo" name="titulo"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="subtitulo">Subtítulo:</label>
                                <div class="mb-2">
                                    <textarea class="editor-ckeditor2" id="subtitulo" name="subtitulo"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="local">Local: <span style="color: red">*</span></label>
                                <input type="text" class="form-control" id="local" name="local"
                                       placeholder="Digite o Local" value="" required>
                            </div>
                            <div class="row justify-content-between">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ano">Ano: <span style="color: red">*</span></label>
                                        <input class="form-control" type="number" min="1900" max="2099" step="1"
                                               name="ano"
                                               value="{{date('Y')}}" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="folhas">Folhas: <span style="color: red">*</span></label>
                                        <input type="number" class="form-control" id="folhas" name="folhas"
                                               placeholder="Quantidade de Folhas" value="" min="0" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ilustracao">Ilustração: <span style="color: red">*</span></label>
                                        <select class="form-control" id="ilustracao" name="ilustracao">
                                            <option value="colorido">Colorido</option>
                                            <option value="preto_branco">Preto e Branco</option>
                                            <option value="nao_possui">Não Possui</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-between">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inclui_anexo">Inclui Anexo ? <span style="color: red">*</span></label>
                                        <select class="form-control" id="inclui_anexo" name="inclui_anexo">
                                            <option value="0">Não</option>
                                            <option value="1">Sim</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inclui_apendice">Inclui Apêndice ? <span style="color: red">*</span></label>
                                        <select class="form-control" id="inclui_apendice" name="inclui_apendice">
                                            <option value="0">Não</option>
                                            <option value="1">Sim</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="anexoArquivo">Anexar os elementos pré-textuais: <span
                                        style="color: red">*</span>
                                </label><br>
                                <input type="file" id="anexo" accept="application/pdf, .docx" name="anexo"
                                       style="margin-bottom: 0px" required>
                                <br>
                                <span id="tipoAnexo"
                                      style="font-size: small; color: gray; margin-top: 0px; margin-bottom: 10px">Tipos permitidos: PDF, DOCX e DOC. </span>
                            </div>
                        </div>
                    </div>
                </div>

                <! –– Dados Especificos ––>

                @if($tipo_documento == 2) <! -- MONOGRAFIA --!>
                <div class="col-md-12 corpoFicha shadow my-4">
                    <div class="row">
                        <div class="col-md-12 cabecalho py-2">
                            <span class="tituloCabecalho">Monografia</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 py-2 textoFicha">
                            <div class="form-group">
                                <label for="nome_orientador">Nome do Orientador: <span
                                        style="color: red">*</span></label>
                                <input type="text" class="form-control" id="nome_orientador" name="nome_orientador"
                                       placeholder="Digite o nome do orientador" value="" required>
                            </div>

                            <div class="form-group">
                                <label for="sobrenome_orientador">Sobrenome do Orientador: <span
                                        style="color: red">*</span></label>
                                <input type="text" class="form-control" id="sobrenome_orientador"
                                       name="sobrenome_orientador"
                                       placeholder="Digite o Sobrenome do orientador" value="" required>
                            </div>

                            <div class="form-group">
                                <label for="nome_coorientador">Nome do Coorientador: </label>
                                <input type="text" class="form-control" id="nome_coorientador"
                                       placeholder="Digite o Nome do Coorientador" value="" name="nome_coorientador">
                            </div>

                            <div class="form-group">
                                <label for="sobrenome_coorientador">Sobrenome do Coorientador: </label>
                                <input type="text" class="form-control" id="sobrenome_coorientador"
                                       placeholder="Digite o Sobrenome do Coorientador" value=""
                                       name="sobrenome_coorientador">
                            </div>

                            <div class="form-group">
                                <label for="nome_segundo_coorientador">Nome do Segundo Coorientador: </label>
                                <input type="text" class="form-control" id="nome_segundo_coorientador"
                                       placeholder="Digite o Nome do Segundo Coorientador" value=""
                                       name="nome_segundo_coorientador">
                            </div>

                            <div class="form-group">
                                <label for="sobrenome_segundo_coorientador">Sobrenome do Segundo Coorientador: </label>
                                <input type="text" class="form-control" id="sobrenome_segundo_coorientador"
                                       placeholder="Digite o Sobrenome do segundo Coorientador" value=""
                                       name="sobrenome_segundo_coorientador">
                            </div>

                            <div class="row justify-content-between">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="tipo_curso">Selecione o Tipo do seu Curso: <span
                                                style="color: red">*</span></label>
                                        <select class="form-control" aria-label="Selecione o tipo do curso"
                                                name="tipo_curso" id="tipo_curso">
                                            <option value="especializacao">Especialização</option>
                                            <option value="graduacao">Graduação</option>
                                            <option value="mestrado">Mestrado</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @elseif($tipo_documento == 4) <! -- TESE --!>
                <div class="col-md-12 corpoFicha shadow my-4">
                    <div class="row">
                        <div class="col-md-12 cabecalho py-2">
                            <span class="tituloCabecalho">Tese</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 py-2 textoFicha">
                            <div class="form-group">
                                <label for="nome_orientador">Nome do Orientador: <span
                                        style="color: red">*</span></label>
                                <input type="text" class="form-control" id="nome_orientador" name="nome_orientador"
                                       placeholder="Digite o Nome do Orientador" value="">
                            </div>

                            <div class="form-group">
                                <label for="sobrenome_orientador">Sobrenome do Orientador: <span
                                        style="color: red">*</span></label>
                                <input type="text" class="form-control" id="sobrenome_orientador"
                                       name="sobrenome_orientador"
                                       placeholder="Digite o Sobrenome do orientador" value="" required>
                            </div>

                            <div class="form-group">
                                <label for="nome_coorientador">Nome do coorientador:</label>
                                <input type="text" class="form-control" id="nome_coorientador" name="nome_coorientador"
                                       placeholder="Digite o Nome do Coorientador" value="">
                            </div>

                            <div class="form-group">
                                <label for="sobrenome_coorientador">Sobrenome do Coorientador: </label>
                                <input type="text" class="form-control" id="sobrenome_coorientador"
                                       placeholder="Digite o Sobrenome do Coorientador" value=""
                                       name="sobrenome_coorientador">
                            </div>

                            <div class="form-group">
                                <label for="programa">Nome do Programa de Pós-Graduação <span
                                        style="color: red">*</span></label>
                                <input type="text" class="form-control" id="programa" name="programa"
                                       placeholder="Digite o Nome do Programa" value="">
                            </div>
                        </div>
                    </div>
                </div>
                @elseif($tipo_documento == 3)
                    <div class="col-md-12 corpoFicha shadow my-4">
                        <div class="row">
                            <div class="col-md-12 cabecalho py-2">
                                <span class="tituloCabecalho">Produto Educacional</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 py-2 textoFicha">
                                <div class="form-group">
                                    <label for="nome_orientador">Nome do Orientador: <span
                                            style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="nome_orientador" name="nome_orientador"
                                           placeholder="Digite o Nome do Orientador" value="" required>
                                </div>

                                <div class="form-group">
                                    <label for="sobrenome_orientador">Sobrenome do Orientador: <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="sobrenome_orientador"
                                           name="sobrenome_orientador"
                                           placeholder="Digite o Sobrenome do Orientador" value="" required>
                                </div>

                                <div class="form-group">
                                    <label for="nome_coorientador">Nome do Coorientador: </label>
                                    <input type="text" class="form-control" id="nome_coorientador"
                                           placeholder="Digite o Nome do Coorientador" value=""
                                           name="nome_coorientador">
                                </div>

                                <div class="form-group">
                                    <label for="sobrenome_coorientador">Sobrenome do Coorientador: </label>
                                    <input type="text" class="form-control" id="sobrenome_coorientador"
                                           placeholder="Digite o Sobrenome do Coorientador" value=""
                                           name="sobrenome_coorientador">
                                </div>
                                <div class="form-group">
                                    <label for="programa">Nome do Programa de Pós-Graduação <span
                                            style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="programa" name="programa"
                                           placeholder="Digite o Nome do Programa" value="">
                                </div>
                                <div class="row justify-content-between">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="tipo_curso">Selecione o Tipo do Produto: <span
                                                    style="color: red">*</span></label>
                                            <select class="form-control" aria-label="Selecione o tipo do produto"
                                                    name="produto" id="produto">
                                                <option value="produto_bibliografico">Produto Bibliográfico</option>
                                                <option value="ativos_propriedade">Ativos de Propriedade (Ex: Patente, Marca e etc.)</option>
                                                <option value="tecnologia_social">Tecnologia Social</option>
                                                <option value="curso_formacao">Curso para Formação Profissional</option>
                                                <option value="produto_editoracao">Produto de Editoração</option>
                                                <option value="material_didatico">Material Didático</option>
                                                <option value="software">Software/Aplicativo (Programa de computador)</option>
                                                <option value="evento">Evento Organizado</option>
                                                <option value="norma">Norma ou Marco Regulatório</option>
                                                <option value="relatorio">Relatório técnico conclusivo</option>
                                                <option value="manual">Manual/Protocolo</option>
                                                <option value="traducao">Tradução</option>
                                                <option value="acervo">Acervo</option>
                                                <option value="base_dados">Base de dados técnico-científica</option>
                                                <option value="cultivar">Cultivar</option>
                                                <option value="produto_comunicacao">Produto de Comunicação</option>
                                                <option value="carta">Carta, mapa ou simila</option>
                                                <option value="produto_sigilo">Produtos/Processos em Sigilo</option>
                                                <option value="taxonomia">Taxonomias, Ontologias e Tesauros</option>
                                                <option value="empresa_social">Empresa ou Organização Social Inovadora</option>
                                                <option value="processo">Processo / Tecnologia e Produto / Material não patenteáveis</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                @elseif($tipo_documento == 1)
                    <div class="col-md-12 corpoFicha shadow my-4">
                        <div class="row">
                            <div class="col-md-12 cabecalho py-2">
                                <span class="tituloCabecalho">Dissertação</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 py-2 textoFicha">
                                <div class="form-group">
                                    <label for="nome_orientador">Nome do Orientador: <span
                                            style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="nome_orientador" name="nome_orientador"
                                           placeholder="Digite o Nome do Orientador" value="" required>
                                </div>

                                <div class="form-group">
                                    <label for="sobrenome_orientador">Sobrenome do Orientador: <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="sobrenome_orientador"
                                           name="sobrenome_orientador"
                                           placeholder="Digite o Sobrenome do Orientador" value="" required>
                                </div>

                                <div class="form-group">
                                    <label for="nome_coorientador">Nome do Coorientador: </label>
                                    <input type="text" class="form-control" id="nome_coorientador"
                                           placeholder="Digite o Nome do Coorientador" value=""
                                           name="nome_coorientador">
                                </div>

                                <div class="form-group">
                                    <label for="sobrenome_coorientador">Sobrenome do Coorientador: </label>
                                    <input type="text" class="form-control" id="sobrenome_coorientador"
                                           placeholder="Digite o Sobrenome do Coorientador" value=""
                                           name="sobrenome_coorientador">
                                </div>
                                <div class="form-group">
                                    <label for="nome_segundo_coorientador">Nome do Segundo Coorientador: </label>
                                    <input type="text" class="form-control" id="nome_segundo_coorientador"
                                           placeholder="Digite o Nome do Segundo Coorientador" value=""
                                           name="nome_segundo_coorientador">
                                </div>

                                <div class="form-group">
                                    <label for="sobrenome_segundo_coorientador">Sobrenome do Segundo Coorientador: </label>
                                    <input type="text" class="form-control" id="sobrenome_segundo_coorientador"
                                           placeholder="Digite o Sobrenome do segundo Coorientador" value=""
                                           name="sobrenome_segundo_coorientador">
                                </div>
                                <div class="form-group">
                                    <label for="programa">Nome do Programa de Pós-Graduação <span
                                            style="color: red">*</span></label>
                                    <input type="text" class="form-control" id="programa" name="programa"
                                           placeholder="Digite o Nome do Programa" value="">
                                </div>

                            </div>
                        </div>
                    </div>
                @endif

                <! -- PALAVRAS CHAVE -- !>
                <div class="col-md-12 corpoFicha shadow my-4">
                    <div class="row">
                        <div class="col-md-12 cabecalho py-2">
                            <span class="tituloCabecalho">Palavras-chave</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 py-2 textoFicha">
                            <div class="form-group">
                                <label for="primeira">Primeira Palavra:<span style="color: red">*</span></label>
                                <input type="text" class="form-control" id="primeira" name="primeira_chave"
                                       placeholder="1. Palavras-chave" value="">
                            </div>

                            <div class="form-group">
                                <label for="segunda">Segunda Palavra:<span style="color: red">*</span></label>
                                <input type="text" class="form-control" id="segunda" name="segunda_chave"
                                       placeholder="2. Palavras-chave" value="">
                            </div>

                            <div class="form-group">
                                <label for="terceira">Terceira Palavra:<span style="color: red">*</span></label>
                                <input type="text" class="form-control" id="terceira" name="terceira_chave"
                                       placeholder="3. Palavras-chave" value="">
                            </div>

                            <div class="form-group">
                                <label for="quarta">Quarta Palavra:</label>
                                <input type="text" class="form-control" id="quarta" name="quarta_chave"
                                       placeholder="4. Palavras-chave" value="">
                            </div>

                            <div class="form-group">
                                <label for="quinta">Quinta Palavra:</label>
                                <input type="text" class="form-control" id="quinta" name="quinta_chave"
                                       placeholder="5. Palavras-chave" value="">
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
                        <button type="submit" class="btn btn-block" id="botaoEnviar"
                                style="background-color: var(--confirmar); border-radius: 0.5rem; color: white;"
                                href="#">
                            Enviar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<script>
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

    var myFile = "";
    var size = 0;
    $('#anexo').on('change', function () {

        if (typeof ($("#anexo")[0].files) != "undefined") {
            size = parseFloat($("#anexo")[0].files[0].size / 1024).toFixed(2);
            if (size > 10000) {
                alert('Os elementos pré-textuais devem ter um tamanho maximo de 10MB Corrija!')
            }

        } else {
            alert("This browser does not support HTML5.");
        }

        myFile = $('#anexo').val();
        var extension = myFile.split('.').pop();
        if (extension == 'pdf' || extension == 'docx' || extension == 'doc') {
            $('#tipoAnexo').css('color', 'green');
        } else {
            $('#tipoAnexo').css('color', 'red');
            alert('O Anexo deve ser de um dos seguites tipos: .pdf, .docx ou .doc.')
        }
    });

    $('#formRequisicao').submit(function (e) {
        myFile = $('#anexo').val();
        var extension = myFile.split('.').pop();
        if (extension == 'pdf' || extension == 'docx' || extension == 'doc') {
            //$('#formRequisicao').submit();
        } else {
            alert('Os elementos pré-textuais devem ser de um dos tipos aceitos: .pdf, .docx ou .doc. Corrija!')
            e.preventDefault();
        }
        if (size > 10000) {
            alert('Os elementos pré-textuais devem ter um tamanho maximo de 10MB Corrija!')
            e.preventDefault();
        }
    });

    var sel = $('#produto');
    var selected = sel.val(); // cache selected value, before reordering
    var opts_list = sel.find('option');
    opts_list.sort(function(a, b) { return $(a).text() > $(b).text() ? 1 : -1; });
    sel.html('').append(opts_list);
    sel.val(selected); // set cached selected value
</script>
@endsection
