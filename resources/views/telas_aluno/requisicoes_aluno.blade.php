@extends('layouts.app')

@section('conteudo')
    <div class="container">
        <div class="row justify-content-sm-center">
            <div class="col-md-11">
                <h2 class="tituloListagem">Suas Requisições</h2>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-11">
                <table class="table table-borderless shadow table-hover mb-2"
                    style="border-radius: 1rem; background-color: white; border: none" id="table">
                    <thead>
                        <tr>
                            <th scope="col" align="center">#</th>
                            <th scope="col" align="center" class="titleColumn text-center" style="cursor: pointer">Curso
                            </th>
                            <th scope="col" align="center" class="titleColumn text-center" style="cursor:pointer;">Data e
                                Hora</th>
                            <th class="text-center" scope="col" align="center" style="cursor:pointer">Tipo de Documento
                            </th>
                            <th class="text-center" scope="col" align="center" style="cursor:pointer">Status</th>
                            <th class="text-center" scope="col" align="center">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requisicoes as $r)
                            <tr>
                                <th class="align-middle" scope="row">{{ $r->id }}</th>
                                <td class="align-middle text-center">
                                    @foreach ($perfis as $p)
                                        @if ($p->id == $r->perfil_id)
                                            {{ $p->default }}
                                        @endif
                                    @endforeach
                                </td>
                                <td class="text-center align-middle">
                                    {{ date_format(date_create($r->data_pedido), 'd/m/Y') }}, {{ $r->hora_pedido }}</td>

                                <td class="align-middle text-center">
                                    <ul class="list-group-item" style="list-style: none; border: none">
                                        @foreach ($requisicoes_documentos as $rd)
                                            @if ($rd->requisicao_id == $r->id)
                                                <!-- Documentos Solicitados -->
                                                @foreach ($documentos as $d)
                                                    @if ($d->id == $rd->documento_id)
                                                        <li>
                                                            @if ($d->tipo == 'Programa de Disciplina')
                                                                {{ $d->tipo }}
                                                                <a data-toggle="tooltip" data-placement="left"
                                                                    title="Informações:{{ $rd['detalhes'] }} ">
                                                                    {{-- Status do indeferimento com imagem do olho --}}
                                                                    <span onclick="exibirAnotacoes('dlgPrograma')"
                                                                        class="glyphicon glyphicon-eye-open"
                                                                        aria-hidden="true"></span>
                                                                    @component('componentes.popup', ['titulo' => 'Informações', 'conteudo' => $rd->detalhes, 'id' => 'dlgPrograma'])
                                                                    @endcomponent
                                                                </a>
                                                            @elseif($d->tipo == 'Outros')
                                                                {{ $d->tipo }}
                                                                <a data-toggle="tooltip" data-placement="left"
                                                                    title="Informações:{{ $rd['detalhes'] }} ">
                                                                    {{-- Status do indeferimento com imagem do olho --}}
                                                                    <span onclick="exibirAnotacoes('dlgOutros')"
                                                                        class="glyphicon glyphicon-eye-open"
                                                                        aria-hidden="true"></span>
                                                                    @component('componentes.popup', ['titulo' => 'Informações', 'conteudo' => $rd->detalhes, 'id' => 'dlgOutros'])
                                                                    @endcomponent
                                                                </a>
                                                            @else
                                                                {{ $d->tipo }}
                                                            @endif
                                                        </li>
                                                    @endif
                                                @endforeach

                                                @foreach ($fichas as $ficha)
                                                    @if ($ficha->id == $rd->ficha_catalografica_id)
                                                        <li>
                                                            Ficha Catalográfica - @if ($ficha->tipo_documento_id == 1)
                                                                Dissertação
                                                            @elseif ($ficha->tipo_documento_id == 2)
                                                                Monografia
                                                            @elseif ($ficha->tipo_documento_id == 3)
                                                                Produto Educacional
                                                            @elseif ($ficha->tipo_documento_id == 4)
                                                                Tese
                                                            @endif
                                                        </li>
                                                    @endif
                                                @endforeach

                                                <li>
                                                    @if ($rd->nada_consta_id != null)
                                                        Comprovante Nada Consta
                                                    @endif
                                                </li>
                                                <li>
                                                    @if ($rd->deposito_id != null)
                                                        Comprovante de Depósito
                                                    @endif
                                                </li>
                                            @endif
                                        @endforeach
                                </td>

                                <td class="text-center align-middle" style="width: 20%">
                                    @php
                                        $tudoAndamento = true;
                                    @endphp

                                    <ul class="list-group-item justify-content-center"
                                        style="list-style: none; border: none">
                                        @foreach ($requisicoes_documentos as $rd)
                                            @if ($rd->requisicao_id == $r->id)
                                                <!-- Documentos Solicitados -->
                                                @if ($rd->status == 'Em andamento')
                                                    <li class="my-1" style="color: #F9CB2A">
                                                        <strong>{{ $rd->status }}</strong>
                                                        <span class="glyphicon glyphicon-time"
                                                            style="overflow: hidden; color:#db6700" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="Sua solicitação está em processamento.">
                                                        </span>
                                                    </li>
                                                @endif
                                                @if ($rd->status == 'Concluído' || $rd->status == 'Concluído - SIGA Desbloqueado')
                                                    @php
                                                        $tudoAndamento = false;
                                                    @endphp
                                                    <li style="color: #00A23E;">
                                                        <strong>{{ $rd->status }}</strong>
                                                        <span class="glyphicon glyphicon-ok-sign"
                                                            style="overflow: hidden; color:green" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="Seu documento está disponível para a retirada.">

                                                        </span>
                                                    </li>
                                                @endif
                                                {{-- Status do indeferimento com imagem do olho --}}
                                                @if ($rd->status == 'Indeferido')
                                                    @php
                                                        $tudoAndamento = false;
                                                    @endphp
                                                    <li style="color: #1C477E">
                                                        <a data-toggle="tooltip" data-placement="left"
                                                            title="Seu pedido foi Indeferido pelo(s) seguinte(s) motivo: {{ $rd->anotacoes }}">
                                                            <span onclick="exibirAnotacoes({{ $rd->id }})"
                                                                class="glyphicon glyphicon-eye-open"
                                                                aria-hidden="true"><strong>{{ $rd->status }}</strong></span>
                                                            @component('componentes.popup', [
                                                                'titulo' => 'Seu pedido foi Indeferido pelo(s) seguinte(s) motivo:',
                                                                'conteudo' => $rd->anotacoes,
                                                                'id' => $rd->id,
                                                            ])
                                                            @endcomponent
                                                        </a>
                                                    </li>
                                                @endif
                                                {{-- Status ficha Concluida --}}
                                                @if ($rd->status == 'Concluido')
                                                    <li style="color:#00A23E">
                                                        <strong>{{ $rd->status }}</strong>
                                                    </li>
                                                @endif
                                                @if ($rd->status == 'Rejeitado')
                                                    <li style="color: #D20101">
                                                        <a data-toggle="tooltip" data-placement="left"
                                                            title="Seu pedido foi rejeitado pelo(s) seguinte(s) motivo: {{ $rd->anotacoes }}">
                                                            <span onclick="exibirAnotacoes({{ $rd->id }})"
                                                                class="glyphicon glyphicon-eye-open"
                                                                aria-hidden="true"><strong>{{ $rd->status }}</strong></span>
                                                            @component('componentes.popup', [
                                                                'titulo' => 'Seu pedido foi rejeitado pelo(s) seguinte(s) motivo:',
                                                                'conteudo' => $rd->anotacoes,
                                                                'id' => $rd->id,
                                                            ])
                                                            @endcomponent
                                                    </li>
                                                @endif
                                            @endif
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="btn-group">
                                        <form id="formExcluirRequisicao" onclick="confirmarExclusao()"
                                            action="{{ route('excluir-requisicao', $r->id) }}" method="POST">
                                            @csrf
                                            @if (!$tudoAndamento)
                                            @else
                                                <button class="btn" type="submit" style="background-color: transparent">
                                                    <img src="images/botao_remover.svg" height="30px"
                                                        title="Excluir Solicitação">
                                                </button>
                                            @endif
                                        </form>
                                        <div class="align-middle">
                                            @if (\App\Models\Requisicao_documento::where('requisicao_id', $r->id)->first()->status == 'Concluido')
                                                @if ($r->requisicao_documento->first()->retificacoes()->exists())
                                                    <a class="btn"
                                                        href="{{ route('baixar-retificacao-aluno', $r->requisicao_documento->first()->retificacoes->last()->id) }}">
                                                        <img src="images/botao_dowload_aquivo.svg" height="30px"
                                                            title="Baixar comprovante">
                                                    </a>
                                                @elseif ($r->requisicao_documento->first()->nada_consta_id != null)
                                                    <a class="btn"
                                                        href="{{ route('baixar-nada-consta-aluno', $r->requisicao_documento->first()->id) }}">
                                                        <img src="images/botao_dowload_aquivo.svg" height="30px"
                                                            title="Baixar comprovante">
                                                    </a>
                                                @elseif ($r->requisicao_documento->first()->deposito_id != null)
                                                    <a class="btn"
                                                        href="{{ route('baixar-deposito-aluno', $r->requisicao_documento->first()->id) }}">
                                                        <img src="images/botao_dowload_aquivo.svg" height="30px"
                                                            title="Baixar comprovante">
                                                    </a>
                                                @else
                                                    <a type="button"
                                                        href={{ route('fichas.gerar', \App\Models\Requisicao_documento::where('requisicao_id', $r->id)->first()->id) }}>
                                                        <button class="btn" style="background-color: transparent">
                                                            <img src="images/botao_dowload_aquivo.svg" height="30px"
                                                                title="Baixar Ficha">
                                                        </button>
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <a class="btn btn-secondary" href="{{ route('cancela-requisicao') }}"
                    style="background-color: var(--padrao); border-radius: 0.5rem; color: white; font-size: 17px">
                    {{ 'Voltar' }}
                </a>
            </div>
        </div>
    </div>

    <script>
        $('#table').DataTable({
            searching: true,

            "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "info": "Exibindo página _PAGE_ de _PAGES_",
                "infoEmpty": "Nenhum registro disponível",
                "zeroRecords": "Nenhum registro disponível",
                "search": "",
                "paginate": {
                    "previous": "Anterior",
                    "next": "Próximo",
                }
            },
            "dom": '<"top"f>rt<"bottom"p><"clear">',
            "order": [],
            "columnDefs": [{
                "targets": [5],
                "orderable": false
            }]
        });

        $('.dataTables_filter').addClass('here');
        $('.dataTables_filter').addClass('');
        $('.here').addClass('center');
        $('.here').removeClass('dataTables_filter');
        $('.table-hover').removeClass('dataTable');
        $('.here').find('input').addClass('search-input');
        $('.here').find('input').addClass('align-middle');
        $('.here').find('label').contents().unwrap();
        $('.here').find('input').wrap(
            '<div class="col-md-12 my-3 py-1" style="background-color: #C2C2C2; border-radius: 1rem;"> <div class="col-md-7 my-2"> <div class="col-md-12 p-1 img-search" style="background-color: white; border-radius: 0.5rem;"> </div> </div> </div>'
            );
        $('.img-search').prepend('<img src="{{ asset('images/search.png') }}" width="25px">');


        function exibirAnotacoes(id) {
            var s = '#' + id;
            $(s).modal('show');
            $(".modal-backdrop").remove();
            console.log(s)
        }
    </script>
@endsection
