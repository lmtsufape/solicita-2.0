@extends('layouts.app')

@section('conteudo')
    <style>
        :root {
            --primary-color: #1B2E4F;
            --secondary-color: #67748B;
            --success-color: #28a745;
            --info-color: #17a2b8;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-bg: #f8f9fc;
            --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .dashboard-container {
            padding: 1rem 0;
            background-color: var(--light-bg);
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.75rem;
            border-left: 5px solid var(--primary-color);
            padding-left: 1rem;
        }

        .secondary-stat {
            background: #f6f8fc;
            padding: 0.6rem 0.9rem;
            transition: all 0.2s ease-in-out;
        }

        .secondary-badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.35em 0.6em;
        }

        .stat-icon {
            font-size: 2rem;
            opacity: 0.3;
            position: absolute;
            right: 1rem;
            top: 1rem;
        }

        .stat-label {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: 0;
        }
        .stat-value-primary { color: var(--primary-color); }
        .stat-value-success { color: var(--success-color); }
        .stat-value-warning { color: #ffb007; }
        .stat-value-danger  { color: var(--danger-color); }

        .filter-section {
            background: white;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .table-container {
            background: white;
            box-shadow: var(--card-shadow);
            padding: 1rem;
        }

        .custom-table thead th {
            background-color: #f8f9fc;
            color: var(--primary-color);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            border-top: none;
            padding: 1rem;
        }

        .custom-table tbody td {
            padding: 1rem;
            vertical-align: middle;
            color: #4e73df;
            font-weight: 500;
        }

        .status-badge {
            padding: 0.35rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .badge-concluido { background-color: #e1f6e5; color: #28a745; }
        .badge-andamento { background-color: #fff4e5; color: #ffc107; }
        .badge-rejeitado { background-color: #ffe5e5; color: #dc3545; }

        .btn-action {
            padding: 0.25rem 0.5rem;
            transition: all 0.2s;
        }

        .btn-action:hover {
            background-color: #f1f3f9;
        }

        .search-input {
            border: 1px solid #d1d3e2;
        }

        .search-btn {

            background-color: var(--primary-color);
            color: white;
        }
        .stat-icon-inline {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.9rem;
            flex-shrink: 0;
            position: relative;
            top: -5px;
        }
    </style>
    <div class="container">
        <!-- Header -->
        <div class="row justify-content-center my-3">
            <div class="col-md-11">
                <h2 class="tituloListagem">Painel do Bibliotecário</h2>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="row justify-content-center">
            <div class="col-md-11">
        <div class="row mb-3">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-left-primary h-100 py-2"
                    style="border-left: 2px solid var(--primary-color) !important;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="stat-icon-inline bg-primary">
                                        <i class="bi bi-clipboard-check"></i>
                                    </div>
                                    <div class="stat-label text-primary ms-2">
                                        Total de Pedidos
                                    </div>
                                </div>
                                <div class="stat-value stat-value-primary">
                                    {{ $stats['total'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-left-success h-100 py-2"
                    style="border-left: 4px solid var(--success-color) !important;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="stat-icon-inline bg-success">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                    <div class="stat-label text-success ms-2">
                                        Concluídos
                                    </div>
                                </div>
                                <div class="stat-value stat-value-success">
                                    {{ $stats['concluidos'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-left-warning h-100 py-2"
                    style="border-left: 4px solid var(--warning-color) !important;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="stat-icon-inline" style="background-color:#ffb007;">
                                        <i class="bi bi-hourglass-split"></i>
                                    </div>
                                    <div class="stat-label ms-2" style="color:#ffb007;">
                                        Em Aberto
                                    </div>
                                </div>
                                <div class="stat-value stat-value-warning">
                                    {{ $stats['em_andamento'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


           <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-danger h-100 py-2"
                style="border-left: 4px solid var(--danger-color) !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex align-items-center mb-2">
                                <div class="stat-icon-inline bg-danger">
                                    <i class="bi bi-x-circle"></i>
                                </div>
                                <div class="stat-label text-danger ms-2">
                                    Rejeitados
                                </div>
                            </div>
                           <div class="stat-value stat-value-danger">
                                {{ $stats['rejeitados'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- Secondary Stats -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="secondary-stat shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="secondary-label">Depósitos TCC</span>
                        <span class="badge badge-primary secondary-badge">
                            {{ $stats['depositos'] }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="secondary-stat shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="secondary-label">Nada Consta</span>
                        <span class="badge badge-primary secondary-badge">
                            {{ $stats['nada_constas'] }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="secondary-stat shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="secondary-label">Fichas Catalográficas</span>
                        <span class="badge badge-primary secondary-badge">
                            {{ $stats['fichas'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        </div>
            <div class="col-md-11">
                <h2 class="tituloListagem">Requisições de Documentos</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-11 my-3">
                <!-- Filters -->
                <div class="filter-section">
                    <form action="{{route('listar-fichas')}}" method="get">
                        <div class="row align-items-end">
                            <div class="col-lg-4 mb-3 mb-lg-0">
                                <label class="small font-weight-bold text-muted">Pesquisar</label>
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control search-input" placeholder="Nome do autor..." value="{{request('search')}}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-outline-primary rounded-end">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                    </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-3 mb-lg-0">
                                <label class="small font-weight-bold text-muted">Data Início</label>
                                <input type="date" name="data_inicio" class="form-control" value="{{request('data_inicio')}}">
                            </div>
                            <div class="col-lg-3 mb-3 mb-lg-0">
                                <label class="small font-weight-bold text-muted">Data Fim</label>
                                <input type="date" name="data_fim" class="form-control" value="{{request('data_fim')}}">
                            </div>
                            <div class="col-lg-2">
                                <button type="submit" class="btn btn-primary btn-block font-weight-bold">
                                    <i class="bi bi-funnel-fill"></i> Filtrar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <table class="table table-borderless shadow table-hover mb-2"
                       style="border-radius: 1rem; background-color: white; border: none" id="table">
                    <thead>
                        <tr>
                            <th scope="col" class="border-bottom-0 py-3">#</th>
                            <th scope="col" class="titleColumn border-bottom-0 py-3" style="cursor:pointer">@sortablelink('autor_nome', 'Autor')</th>
                            <th scope="col" class="titleColumn text-center border-bottom-0 py-3"
                                style="cursor:pointer">Tipo do Documento
                            </th>
                            <th scope="col" class="titleColumn text-center border-bottom-0 py-3"
                                style="cursor:pointer">@sortablelink('entity_created_at', 'Data da Requisição')
                            </th>
                            <th scope="col" class="text-center border-bottom-0 py-3">@sortablelink('status', 'Status')</th>
                            <th scope="col" class="text-center border-bottom-0 py-3">Ação</th>
                            <th scope="col" class="text-center border-bottom-0 py-3">@sortablelink('entity_updated_at', 'Data de análise')</th>
                        </tr>
                    </thead>
                    <tbody class="">
                        @foreach($requisicoesFichas as $i => $requisicao)
                            @if($requisicao->ficha_catalografica_id != null)
                                <tr>
                                    <td class="align-middle text-center" scope="row">
                                        {{$i + 1 + ($requisicoesFichas->firstItem() - 1) }}
                                    </td>
                                    <td class="align-middle text-center">
                                        {{$requisicao->fichaCatalografica->autor_nome}}
                                    </td>
                                    <td class="align-middle text-center">
                                        Ficha Catalográfica -
                                        @if ($requisicao->fichaCatalografica->tipo_documento_id == 2)Monografia
                                        @elseif ($requisicao->fichaCatalografica->tipo_documento_id == 4)Tese
                                        @elseif ($requisicao->fichaCatalografica->tipo_documento_id == 3)Produto Educacional
                                        @elseif ($requisicao->fichaCatalografica->tipo_documento_id == 1)Dissertação
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ date('d/m/Y H:i:s', strtotime($requisicao->fichaCatalografica->created_at)) }}
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($requisicao->status == 'Concluido')
                                            <p class="mb-0" style="color: #1d643b; "><strong>Concluído</strong></p>
                                        @elseif($requisicao->status == 'Em andamento')
                                            <p class="mb-0" style="color: #857b26"><strong>Em andamento</strong></p>
                                        @elseif($requisicao->status == 'Rejeitado')
                                            <p class="mb-0" style="color: #4c110f"><strong>Rejeitado</strong></p>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <div class="btn-group">
                                            <?php
                                            // $data_bibi = date_create_from_format('Y-m-d H:i:s', $requisicao->updated_at);
                                            // $data_agora = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));&& (date_diff($data_bibi, $data_agora)->h < 2)
                                            ?>
                                            @if($requisicao->status == 'Em andamento')
                                                @if($requisicao->bibliotecario_id != null && $requisicao->bibliotecario_id != $bibliotecario->id)
                                                    <a class="btn btn-modal" href="#" data-toggle="modal" data-target="#modalEditarFicha">
                                                        <img src="images/botao_editar_proibido.svg" height="30px" title="Botão de Editar - Alguém já está editando">
                                                    </a>
                                                    <a class="btn rounded-0" href="{{ route('visualizar-ficha', $requisicao->id) }}">
                                                        <img src="images/botao_visualizar.svg" height="30px" title="Botão de Visualizar Ficha">
                                                    </a>
                                                                                                                                                                    <!-- Modal de Edição precisa ficar aqui por conta do foreach, na possibilidade de nao haver nada ele da problema na $requisicao -->
                                                        <div class="modal fade" id="modalEditarFicha" tabindex="-1" role="dialog" aria-labelledby="modalEditarFichaLabel">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="modalEditarFichaLabel">Edição de Ficha</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Alguém já está editando esta ficha. O que você deseja fazer?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <a href="{{ route('editar-ficha', $requisicao->id) }}" class="btn btn-primary">Editar</a>
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                @else
                                                    <a class="btn" href="{{ route('visualizar-ficha', $requisicao->id) }}">
                                                        <img src="images/botao_visualizar.svg" height="30px" title="Botão de Visualizar Ficha">
                                                    </a>
                                                @endif
                                            @endif
                                            @if($requisicao->status == 'Concluido')
                                                <a class="btn" href="{{ route('visualizar-ficha', $requisicao->id) }}">
                                                    <img src="images/botao_visualizar.svg" height="30px" title="Botão de Visualizar Ficha">
                                                </a>
                                            @endif
                                            @if($requisicao->status == 'Rejeitado')
                                                <a class="btn" data-toggle="modal"
                                                    data-target="#exampleModal{{$requisicao->id}}">
                                                    <img src="images/botao_info.svg" height="30px" title="Botão de Informação">
                                                </a>
                                                <!-- Modal -->
                                                <div class="modal fade" id="exampleModal{{$requisicao->id}}"
                                                        role="dialog" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">
                                                                    Status da Análise
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                @if($requisicao->status == 'Concluido')
                                                                    <p style="margin-left: 3px">Requisição analisada e
                                                                        aprovada por:
                                                                        <Strong>{{\App\Models\User::where('id',\App\Models\Bibliotecario::where('id',$requisicao->bibliotecario_id)->first()->user_id)->first()->name}}</Strong>
                                                                    </p>
                                                                @elseif($requisicao->status == 'Rejeitado' && $requisicao->bibliotecario_id != null)
                                                                    <p style="margin: 1rem">Requisição analisada e
                                                                        rejeitada por:
                                                                        <Strong>{{\App\Models\User::where('id',\App\Models\Bibliotecario::where('id',$requisicao->bibliotecario_id)->first()->user_id)->first()->name}}</Strong>
                                                                        <br>
                                                                    </p>
                                                                    <p style="margin-left: 1rem">Motivo: <strong
                                                                            style="color: #4c110f">{{ $requisicao->anotacoes }}</strong>
                                                                    </p>
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Fechar
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($requisicao->status != 'Em andamento')
                                            {{ date('d/m/Y H:i:s', strtotime($requisicao->updated_at)) }}
                                        @endif
                                    </td>
                                </tr>
                            @elseif($requisicao->nada_consta_id != null)
                                <tr>
                                    <td class="align-middle text-center" scope="row">
                                        {{$i + 1 + ($requisicoesFichas->firstItem() - 1) }}
                                    </td>
                                    <td class="align-middle text-center">
                                        {{$requisicao->nadaConsta->autor_nome ?? $requisicao->aluno->user->name}}
                                    </td>
                                    <td class="align-middle text-center">
                                        Comprovante Nada Consta
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ date('d/m/Y H:i:s', strtotime($requisicao->nadaConsta->created_at)) }}
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($requisicao->status == 'Concluido')
                                            <p class="mb-0" style="color: #1d643b; "><strong>Concluído</strong></p>
                                        @elseif($requisicao->status == 'Em andamento')
                                            <p class="mb-0" style="color: #857b26"><strong>Em andamento</strong></p>
                                        @elseif($requisicao->status == 'Rejeitado')
                                            <p class="mb-0" style="color: #4c110f"><strong>Rejeitado</strong></p>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <div class="btn-group">
                                            <?php
                                            $data_bibi = date_create_from_format('Y-m-d H:i:s', $requisicao->updated_at);
                                            $data_agora = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
                                            ?>
                                            @if($requisicao->status == 'Em andamento')
                                                @if($requisicao->bibliotecario_id != null && (date_diff($data_bibi, $data_agora)->h < 2))
                                                    <a class="btn" href="{{ route('avaliar-nada-consta', $requisicao->id) }}">
                                                        <img src="images/botao_editar_proibido.svg" height="30px" title="Avaliar solicitação - Alguém já está avaliando">
                                                    </a>
                                                @else
                                                    <a class="btn" href="{{ route('avaliar-nada-consta', $requisicao->id) }}">
                                                        <img src="images/botao_editar.svg" height="30px" title="Avaliar solicitação">
                                                    </a>
                                                @endif
                                            @endif

                                            @if($requisicao->status == 'Concluido')
                                                <a class="btn" href="{{ route('visualizar-nada-consta', $requisicao->id) }}">
                                                    <img src="images/botao_visualizar.svg" height="30px" title="Visualizar solicitação">
                                                </a>
                                            @endif
                                            @if($requisicao->status == 'Rejeitado')
                                                <a class="btn" data-toggle="modal"
                                                    data-target="#exampleModal{{$requisicao->id}}">
                                                    <img src="images/botao_info.svg" height="30px" title="Botão de Informação">
                                                </a>
                                                <!-- Modal -->
                                                <div class="modal fade" id="exampleModal{{$requisicao->id}}"
                                                        role="dialog" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Status da Análise</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                @if($requisicao->status == 'Concluido')
                                                                    <p style="margin-left: 3px">Requisição analisada e
                                                                        aprovada por:
                                                                        <Strong>{{\App\Models\User::where('id',\App\Models\Bibliotecario::where('id',$requisicao->bibliotecario_id)->first()->user_id)->first()->name}}</Strong>
                                                                    </p>
                                                                @elseif($requisicao->status == 'Rejeitado' && $requisicao->bibliotecario_id != null)
                                                                    <p style="margin: 1rem">Requisição analisada e
                                                                        rejeitada por:
                                                                        <Strong>{{\App\Models\User::where('id',\App\Models\Bibliotecario::where('id',$requisicao->bibliotecario_id)->first()->user_id)->first()->name}}</Strong>
                                                                        <br>
                                                                    </p>
                                                                    <p style="margin-left: 1rem">Motivo: <strong
                                                                            style="color: #4c110f">{{ $requisicao->anotacoes }}</strong>
                                                                    </p>
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Fechar
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($requisicao->status != 'Em andamento')
                                            {{ date('d/m/Y H:i:s', strtotime($requisicao->updated_at)) }}
                                        @endif
                                    </td>
                                </tr>
                            @elseif($requisicao->deposito_id != null)
                                <tr>
                                    <td class="align-middle text-center" scope="row">
                                        {{$i + 1 + ($requisicoesFichas->firstItem() - 1) }}
                                    </td>
                                    <td class="align-middle text-center">
                                        {{$requisicao->deposito->autor_nome ?? $requisicao->aluno->user->name}}
                                    </td>
                                    <td class="align-middle text-center">
                                        Comprovante Depósito
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ date('d/m/Y H:i:s', strtotime($requisicao->deposito->created_at)) }}
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($requisicao->status == 'Concluido')
                                            <p class="mb-0" style="color: #1d643b; "><strong>Concluído</strong></p>
                                        @elseif($requisicao->status == 'Em andamento')
                                            <p class="mb-0" style="color: #857b26"><strong>Em andamento</strong></p>
                                        @elseif($requisicao->status == 'Rejeitado')
                                            <p class="mb-0" style="color: #4c110f"><strong>Rejeitado</strong></p>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <div class="btn-group">
                                            <?php
                                            $data_bibi = date_create_from_format('Y-m-d H:i:s', $requisicao->updated_at);
                                            $data_agora = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
                                            ?>
                                            @if($requisicao->status == 'Em andamento')
                                                @if($requisicao->bibliotecario_id != null && (date_diff($data_bibi, $data_agora)->h < 2))
                                                    <a class="btn" href="{{ route('avaliar-deposito', $requisicao->id) }}">
                                                        <img src="images/botao_editar_proibido.svg" height="30px" title="Avaliar solicitação - Alguém já está avaliando">
                                                    </a>
                                                    <a class="btn rounded-0" href="{{ route('visualizar-deposito', $requisicao->id) }}">
                                                        <img src="images/botao_visualizar.svg" height="30px" title="Visualizar solicitação">
                                                    </a>
                                                @else
                                                    <a class="btn" href="{{ route('avaliar-deposito', $requisicao->id) }}">
                                                        <img src="images/botao_editar.svg" height="30px" title="Avaliar solicitação">
                                                    </a>
                                                    <a class="btn" href="{{ route('visualizar-deposito', $requisicao->id) }}">
                                                        <img src="images/botao_visualizar.svg" height="30px" title="Visualizar solicitação">
                                                    </a>
                                                @endif
                                            @endif
                                            @if($requisicao->status == 'Concluido')
                                                <a class="btn" href="{{ route('visualizar-deposito', $requisicao->id) }}">
                                                    <img src="images/botao_visualizar.svg" height="30px" title="Visualizar solicitação">
                                                </a>
                                            @endif
                                            @if($requisicao->status == 'Rejeitado')
                                                <a class="btn" data-toggle="modal"
                                                data-target="#exampleModal{{$requisicao->id}}">
                                                    <img src="images/botao_info.svg" height="30px" title="Botão de Informação">
                                                </a>
                                                <!-- Modal -->
                                                <div class="modal fade" id="exampleModal{{$requisicao->id}}"
                                                    role="dialog" aria-labelledby="exampleModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Status da Análise</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                @if($requisicao->status == 'Concluido')
                                                                    <p style="margin-left: 3px">Requisição analisada e
                                                                        aprovada por:
                                                                        <Strong>{{\App\Models\User::where('id',\App\Models\Bibliotecario::where('id',$requisicao->bibliotecario_id)->first()->user_id)->first()->name}}</Strong>
                                                                    </p>
                                                                @elseif($requisicao->status == 'Rejeitado' && $requisicao->bibliotecario_id != null)
                                                                    <p style="margin: 1rem">Requisição analisada e
                                                                        rejeitada por:
                                                                        <Strong>{{\App\Models\User::where('id',\App\Models\Bibliotecario::where('id',$requisicao->bibliotecario_id)->first()->user_id)->first()->name}}</Strong>
                                                                        <br>
                                                                    </p>
                                                                    <p style="margin-left: 1rem">Motivo: <strong
                                                                            style="color: #4c110f">{{ $requisicao->anotacoes }}</strong>
                                                                    </p>
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Fechar
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($requisicao->status != 'Em andamento')
                                            {{ date('d/m/Y H:i:s', strtotime($requisicao->updated_at)) }}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination d-flex flex-column">
                    {{ $requisicoesFichas->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                </div>
            </div>
    </div>
    <!-- Link para ícones do Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection
