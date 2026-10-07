<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ficha Catalográfica</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        /** Define the margins of your page **/
        @page {
            margin: 0cm 0cm;
        }

        /** Define now the real margins of every page in the PDF **/
        body {
            margin-top: 15cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 2cm;
        }

        p {
            margin: 0 !important;
            padding: 0;
            display: inline;
        }

        img {
            width: 3.66cm;
            height: 2.52cm;
            margin-left: 80px;
            margin-top: 0.4cm;
        }

        main {
            margin-top: 1.2cm;
        }

        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 3cm;
        }

        /** Define the footer rules **/
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
        }

        h1 {
            font-size: 18px;
            text-align: center;
        }

        h2 {
            margin-left: 5px !important;
            font-size: 18px;
            margin-bottom: 0px;
            padding-bottom: 0px;
        }

        .pagenum:before {
            content: counter(page);
        }

        table {
            width: 100%;
            margin: 0px;
            padding: 0px;
        }

        tr {
            margin: 0px;
            padding: 0px;
        }

        td {
            margin: 0px;
            padding: 0px;
        }

        .page_break {
            page-break-before: always;
        }

        .centralizar {
            margin-left: auto;
            margin-right: auto;
        }

        .quadrado {
            border: 1px solid;
            width: 12.5cm;
            height: 7.5cm;
            margin-left: auto;
            margin-right: auto;
        }

        .recuo {
            text-align: justify;
        }

        span.recuo {
            visibility: hidden;
        }

        .fixCDD {
            position: fixed;
            bottom: 0px;
        }


    </style>

</head>

<body>

<div style="font-family: 'Times New Roman', Times, serif; font-size: 10px; text-align: center; margin-bottom: 10px">

    <!-- Topo da ficha -->

    <p>Ficha de identificação da obra elaborada pelo autor, através do programa de geração automática da UFAPE</p>

</div>
<div class="quadrado">
    <table style="font-family: 'Times New Roman', Times, serif; font-size: 10px;  margin-top: 12px;">


        <tr>
            <td style="width: 13%; vertical-align:top; float: top"><br><span style="margin-left: 10px; top: 16.1% ">{{ $ficha->cutter}}</span></td>

            <td>
                <table>
                    <!-- Parte padrão -->
                    <tr>
                        <td>{{ $ficha->autor_sobrenome }}, {{ $ficha->autor_nome }}</td>
                    </tr>
                    <tr>
                        @php
                            $titulo = $ficha->titulo;
                        @endphp
                        <td class="recuo"><span class="recuo">{{substr($ficha->autor_sobrenome, 0, 3)}}</span>{!! $titulo !!}@if($ficha->subtitulo != null): {!! $ficha->subtitulo !!}@endif
                            / {{ $ficha->autor_nome . ' ' . $ficha->autor_sobrenome . '.'}} – {{ $ficha->local }},
                             {{ $ficha->ano }}.
                        </td>
                    </tr>
                    <tr>
                        <td class="recuo"><span class="recuo">{{substr($ficha->autor_sobrenome, 0, 3)}}</span>{{ $ficha->folhas }} f. @if($ficha->ilustracao == 'colorido'): il.
                            color. @elseif($ficha->ilustracao == 'nao_possui')@else: il. @endif</td>
                    </tr>
                    <!-- Parte da Monografia -->
                    @if($tipo_documento == 'Monografia')
                        <tr>
                            <td class="recuo" style="padding-top: 10px"><span class="recuo">{{substr($ficha->autor_sobrenome, 0, 3)}}</span>Orientador(a): {{ $documento->nome_orientador }} {{ $documento->sobrenome_orientador }}.
                            </td>
                        </tr>
                        <tr>
                            @if($documento->nome_coorientador != null)
                                <td class="recuo">
                                    <span class="recuo">{{substr($ficha->autor_sobrenome, 0, 3)}}</span>Coorientador(a): {{ $documento->nome_coorientador }} {{ $documento->sobrenome_coorientador }}.
                                </td>
                            @endif
                        </tr>
                        @if(!is_null($documento->nome_segundo_coorientador))
                            <tr>
                                <td class="recuo">
                                    <span class="recuo">{{substr($ficha->autor_sobrenome, 0, 3)}}</span>Coorientador(a): {{ $documento->nome_segundo_coorientador }} {{ $documento->sobrenome_segundo_coorientador }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td class="recuo"><span class="recuo">{{substr($ficha->autor_sobrenome, 0, 3)}}</span>Monografia @if($documento->tipo_curso == 'especializacao')
                                    (Especialização) @elseif($documento->tipo_curso == 'graduacao')
                                    (Graduação) @elseif($documento->tipo_curso == 'mestrado') (Mestrado) @endif -
                                @if($unidade->nome == 'UPE - Campus Garanhuns')<p>Universidade de Pernambuco</p>, @else
                                    <p>Universidade Federal do Agreste de Pernambuco</p>,@endif
                                {{ str_replace('(Mestrado) ', '', str_replace('(Graduação) ', '', $documento->curso)) }},
                                {{ $ficha->local }}, BR-PE, {{ $ficha->ano }}.
                            </td>
                        </tr>

                        <!-- Parte da tese -->
                    @elseif($tipo_documento == 'Tese')
                        <tr>
                            <td class="recuo" style="padding-top: 10px"><span class="recuo">{{substr($ficha->autor_sobrenome, 0, 3)}}</span>Orientador(a): {{ $documento->nome_orientador }} {{ $documento->sobrenome_orientador }}.
                            </td>
                        </tr>
                        <tr>
                            @if($documento->nome_coorientador != null)
                            <td class="recuo"><span class="recuo">{{substr($ficha->autor_sobrenome, 0, 3)}}</span>Coorientador(a):  {{ $documento->nome_coorientador }} {{ $documento->sobrenome_coorientador }}.

                            </td>@endif
                        </tr>
                        <tr>
                            <td class="recuo"><span class="recuo">{{substr($ficha->autor_sobrenome, 0, 3)}}</span>Tese (Doutorado) - @if($unidade->nome == 'UPE - Campus Garanhuns')<p>
                                    Universidade de Pernambuco</p>, @else
                                    <p>Universidade Federal do Agreste de Pernambuco</p>,@endif
                                 {{ $documento->programa }},
                                {{ $ficha->local }}, BR-PE, {{ $ficha->ano }}.
                            </td>
                        </tr>
                    @elseif($tipo_documento == 'ProgramaEduc')
                        <tr>
                            <td class="recuo" style="padding-top: 10px"><span class="recuo">{{substr($ficha->autor_sobrenome, 0, 3)}}</span>Orientador(a): {{ $documento->nome_orientador }} {{ $documento->sobrenome_orientador }}.
                            </td>
                        </tr>
                        <tr>
                            @if($documento->nome_coorientador != null)
                            <td class="recuo"><span class="recuo">{{substr($ficha->autor_sobrenome, 0, 3)}}</span>Coorientador(a): {{ $documento->nome_coorientador }} {{ $documento->sobrenome_coorientador }}.
                                    </td>@endif
                        </tr>
                        <tr>
                            <td class="recuo"><span class="recuo">{{substr($ficha->autor_sobrenome, 0, 3)}}</span>Produto técnico/tecnológico que acompanha Dissertação (Mestrado)
                                - @if($documento->produto == "produto_bibliografico")Produto Bibliográfico
                                @elseif($documento->produto == 'ativos_propriedade')Ativos de Propriedade
                                @elseif($documento->produto == 'tecnologia_social')Tecnologia Social
                                @elseif($documento->produto == 'curso_formacao')Curso para Formação
                                @elseif($documento->produto == 'produto_editoracao')Produto de Editoração
                                @elseif($documento->produto == 'material_didatico')Material Didático
                                @elseif($documento->produto == 'software')Software/Aplicativo
                                @elseif($documento->produto == 'evento')Evento Organizado
                                @elseif($documento->produto == 'norma')Norma ou Marco Regulatório
                                @elseif($documento->produto == 'relatorio')Relatório técnico conclusivo
                                @elseif($documento->produto == 'manual')Manual/Protocolo
                                @elseif($documento->produto == 'traducao')Tradução
                                @elseif($documento->produto == 'acervo')Acervo
                                @elseif($documento->produto == 'base_dados')Base de Dados Técnico-Científica
                                @elseif($documento->produto == 'cultivar')Cultivar
                                @elseif($documento->produto == 'produto_comunicacao')Produto de Comunicação
                                @elseif($documento->produto == 'carta')Carta, mapa ou simila
                                @elseif($documento->produto == 'produto_sigilo')Produtos/Processos em Sigilo
                                @elseif($documento->produto == 'taxonomia')Taxonomias, Ontologias e Tesauros
                                @elseif($documento->produto == 'empresa_social')Empresa ou Organização Social Inovadora
                                @elseif($documento->produto == 'processo')Processo / Tecnologia e Produto / Material não
                                    patenteáveis
                                @endif - @if($unidade->nome == 'UPE - Campus Garanhuns')<p>Universidade de
                                    Pernambuco</p>,@else
                                    <p>Universidade Federal do Agreste de Pernambuco</p>,@endif
                                 {{ $documento->programa }},
                                {{ $ficha->local }}, BR-PE, {{ $ficha->ano }}.
                            </td>
                        </tr>

                        <!-- Parte da Dissertacao -->
                    @else
                        <tr>
                            <td class="recuo" style="padding-top: 10px"><span class="recuo">{{substr($ficha->autor_sobrenome, 0, 3)}}</span>Orientador(a): {{ $documento->nome_orientador }} {{ $documento->sobrenome_orientador }}.
                            </td>
                        </tr>
                        <tr>
                            @if($documento->nome_coorientador != null)
                                <td class="recuo">
                                    <span class="recuo">{{substr($ficha->autor_sobrenome, 0, 3)}}</span>Coorientador(a): {{ $documento->nome_coorientador }} {{ $documento->sobrenome_coorientador }}.
                                </td>
                            @endif
                        </tr>
                        @if(!is_null($documento->nome_segundo_coorientador))
                            <tr>
                                <td class="recuo">
                                    <span class="recuo">{{substr($ficha->autor_sobrenome, 0, 3)}}</span>Coorientador(a): {{ $documento->nome_segundo_coorientador }} {{ $documento->sobrenome_segundo_coorientador }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td class="recuo"><span class="recuo">{{substr($ficha->autor_sobrenome, 0, 3)}}</span>Dissertação (Mestrado) - @if($unidade->nome == 'UPE - Campus Garanhuns')
                                    <p>Universidade de Pernambuco</p>, @else
                                    <p>Universidade Federal do Agreste de Pernambuco</p>,@endif
                                 {{ $documento->programa }},
                                {{ $ficha->local }}, BR-PE, {{ $ficha->ano }}.
                            </td>
                        </tr>
                    <!--<p>Programa: {{ $documento->programa }}</p>
            <p>Campus: {{ $unidade->nome }}</p> -->
                    @endif

                    <tr>
                        <td class="recuo" style="padding-top: 10px"><span class="recuo">{{substr($ficha->autor_sobrenome, 0, 3)}}</span><span>Inclui referências</span>@if($ficha->inclui_anexo == '1' && $ficha->inclui_apendice == 0) e anexo.
                            @elseif($ficha->inclui_anexo == '0' && $ficha->inclui_apendice == 1) e apêndice(s).
                            @elseif($ficha->inclui_anexo == '1' && $ficha->inclui_apendice == 1), anexo e apêndice(s).
                            @else().
                            @endif</td>
                    </tr>

                    <!-- Palavras chave -->
                    @if($tipo_documento == 'Monografia')
                        <tr>
                            <td class="recuo" style="padding-top: 10px"><span class="recuo">{{substr($ficha->autor_sobrenome, 0, 3)}}</span>@for ($i = 0; $i < sizeof($palavras); $i++){{ ($i + 1) }}. {{ $palavras[$i]->palavra }}.
                                @endfor I. {{ $documento->sobrenome_orientador }}, {{ $documento->nome_orientador }}
                                (orient.)
                                @if($documento->nome_coorientador != null && is_null($documento->nome_segundo_coorientador))
                                    II. {{ $documento->sobrenome_coorientador }}, {{ $documento->nome_coorientador }}
                                    (coorient.)
                                    III. @if($unidade->nome == 'UPE - Campus Garanhuns')<p>Universidade de
                                        Pernambuco</p> @else <p>Universidade Federal do Agreste de Pernambuco</p>@endif
                                    IV. Título
                                @elseif($documento->nome_coorientador != null && $documento->nome_segundo_coorientador != null)
                                    II. {{ $documento->sobrenome_coorientador }}, {{ $documento->nome_coorientador }}
                                    (coorient.)
                                    III. {{ $documento->sobrenome_segundo_coorientador }}, {{ $documento->nome_segundo_coorientador }}
                                    (coorient.)
                                    IV. @if($unidade->nome == 'UPE - Campus Garanhuns')<p>Universidade de
                                        Pernambuco</p> @else <p>Universidade Federal do Agreste de Pernambuco</p>@endif
                                    V. Título
                                @else
                                    II. @if($unidade->nome == 'UPE - Campus Garanhuns')<p>Universidade de
                                        Pernambuco</p>@else <p>Universidade Federal do Agreste de Pernambuco</p>@endif
                                    III. Título
                                @endif</td>

                        </tr>

                    @elseif($tipo_documento == 'Tese')
                        <tr>
                            <td class="recuo" style="padding-top: 10px"><span class="recuo">{{substr($ficha->autor_sobrenome, 0, 3)}}</span>@for ($i = 0; $i < sizeof($palavras); $i++){{ ($i + 1) }}. {{ $palavras[$i]->palavra }}.
                                @endfor I. {{ $documento->sobrenome_orientador }}, {{ $documento->nome_orientador }}
                                (orient.)
                                @if($documento->nome_coorientador != null)
                                    II. {{ $documento->sobrenome_coorientador }}, {{ $documento->nome_coorientador }}
                                    (coorient.)
                                    III. @if($unidade->nome == 'UPE - Campus Garanhuns')<p>Universidade de
                                        Pernambuco</p> @else <p>Universidade Federal do Agreste de Pernambuco</p>@endif
                                    {{ $documento->programa }} IV. Título
                                @else II. @if($unidade->nome == 'UPE - Campus Garanhuns')<p>Universidade de
                                    Pernambuco</p> @else <p>Universidade Federal do Agreste de Pernambuco</p>@endif
                                {{ $documento->programa }} III. Título

                                @endif</td>

                        </tr>
                    @elseif($tipo_documento == 'ProgramaEduc')
                        <tr>
                            <td class="recuo" style="padding-top: 10px"><span class="recuo">{{substr($ficha->autor_sobrenome, 0, 3)}}</span>@for ($i = 0; $i < sizeof($palavras); $i++){{ ($i + 1) }}. {{ $palavras[$i]->palavra }}.
                                @endfor I. {{ $documento->sobrenome_orientador }}, {{ $documento->nome_orientador }}(orient.)
                                @if($documento->nome_coorientador != null)
                                    II. {{ $documento->sobrenome_coorientador }}, {{ $documento->nome_coorientador }}(coorient.)
                                    III. @if($unidade->nome == 'UPE - Campus Garanhuns')<p>Universidade de
                                        Pernambuco</p> @else <p>Universidade Federal do Agreste de Pernambuco</p>@endif
                                    {{ $documento->programa }} IV. Título
                                @else
                                    II. @if($unidade->nome == 'UPE - Campus Garanhuns')<p>Universidade de
                                        Pernambuco</p>@else <p>Universidade Federal do Agreste de Pernambuco</p>@endif
                                    {{ $documento->programa }} III. Título
                                @endif
                            </td>

                        </tr>

                    @else
                        <tr>
                            <td class="recuo" style="padding-top: 10px"><span class="recuo">{{substr($ficha->autor_sobrenome, 0, 3)}}</span>@for ($i = 0; $i < sizeof($palavras); $i++){{ ($i + 1) }}. {{ $palavras[$i]->palavra }}.
                                @endfor I. {{ $documento->sobrenome_orientador }}, {{ $documento->nome_orientador }}
                                (orient.)
                                @if($documento->nome_coorientador != null && is_null($documento->nome_segundo_coorientador))
                                    II. {{ $documento->sobrenome_coorientador }}, {{ $documento->nome_coorientador }}
                                    (coorient.)
                                    III. @if($unidade->nome == 'UPE - Campus Garanhuns')<p>Universidade de
                                        Pernambuco</p> @else <p>Universidade Federal do Agreste de Pernambuco</p>@endif
                                    {{ $documento->programa }} IV. Título
                                @elseif($documento->nome_coorientador != null && $documento->nome_segundo_coorientador != null)
                                    II. {{ $documento->sobrenome_coorientador }}, {{ $documento->nome_coorientador }}
                                    (coorient.)
                                    III. {{ $documento->sobrenome_segundo_coorientador }}, {{ $documento->nome_segundo_coorientador }}
                                    (coorient.)
                                    IV. @if($unidade->nome == 'UPE - Campus Garanhuns')<p>Universidade de
                                        Pernambuco</p> @else <p>Universidade Federal do Agreste de Pernambuco</p>@endif
                                    V. Título

                                @else
                                    II. @if($unidade->nome == 'UPE - Campus Garanhuns')<p>Universidade de
                                        Pernambuco</p>@else <p>Universidade Federal do Agreste de Pernambuco</p>@endif
                                    {{ $documento->programa }} III. Título
                                @endif</td>

                        </tr>

                    @endif

                    <!-- Parte do CDD -->
                    <tr>
                        <td align=right><span
                                style="margin-right: 5px;">CDD {{ $ficha->classificacao }}</span></td>
                    </tr>
                </table>
            </td>

            <td valign=top style="width: 6%"></td>
        </tr>

    </table>
</div>

</body>
</html>
