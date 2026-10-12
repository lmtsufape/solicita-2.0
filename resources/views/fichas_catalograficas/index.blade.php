@extends('layouts.app')

@section('conteudo')
@section('navbar2.blade.php')

@endsection

<div class="container py-4">

    <div
        class="row mx-0 p-0 mb-2"
        style="border-bottom: var(--textcolor) 2px solid;"
    >
        <div class="col-12 p-0">
            <h1 class="tituoRequisicao mb-1">
                Fichas Catalográficas
            </h1>
        </div>
    </div>

    <p class="text-muted mb-4">
        Consulte suas fichas catalográficas e baixe-as quando necessário.
    </p>


    <div class="card border-0 rounded-4 shadow-sm overflow-hidden">

        <div class="card-body p-4">

            <div class="d-flex g-3 justify-content-end mb-4">
                <a href="{{ route('fichas.create') }}"
                    class="btn btn-success px-4 w-25"
                >
                    <i class="bi bi-plus-lg me-2"></i>
                    Gerar nova ficha
                </a>
            </div>


            <div class="table-responsive border rounded-3">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3 py-3">Tipo</th>

                            <th class="px-3 py-3">Título do trabalho</th>

                            <th class="px-3 py-3">Última geração</th>

                            <th class="px-3 py-3">Ações</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($fichas as $ficha)
                            <tr>
                                <td class="px-3 py-3">

                                    @php
                                        $tipo = $ficha->tipoFichaCatalografica?->slug ?? '-';
                                    @endphp

                                    <span
                                        class="badge rounded-pill px-3 py-2
                                        {{
                                            $tipo === 'monografia'
                                                ? 'bg-primary-subtle text-primary'
                                                : 'bg-info-subtle text-info-emphasis'
                                        }}"
                                    >
                                        {{ $ficha->tipoFichaCatalografica?->nome ?? '-' }}
                                    </span>

                                </td>


                                <td class="px-3 py-3">

                                    <div class="fw-semibold"style="max-width: 320px;">
                                        {!! $ficha->titulo !!}
                                    </div>

                                </td>

                                <td class="px-3 py-3">

                                    @if($ficha->updated_at)

                                        <span class="d-block">
                                            {{ $ficha->updated_at->format('d/m/Y') }}
                                        </span>

                                        <small class="text-muted">
                                            às {{ $ficha->updated_at->format('H:i') }}
                                        </small>

                                    @else

                                        -

                                    @endif

                                </td>


                                <td class="px-3 py-3">

                                    <div class="d-flex flex-wrap gap-2">

                                        <a
                                            href="{{ route('fichas.gerar', $ficha->id) }}"
                                            class="btn btn-primary btn-sm px-3"
                                            title="Baixar ficha"
                                        >
                                            <i class="bi bi-download me-1"></i>
                                            Baixar
                                        </a>


                                        <a
                                            href="{{ route('fichas.edit', $ficha) }}"
                                            class="btn btn-outline-primary btn-sm px-3"
                                            title="Atualizar ficha"
                                        >
                                            <i class="bi bi-arrow-clockwise me-1"></i>
                                            Atualizar ficha
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-5"
                                >

                                    <i
                                        class="bi bi-file-earmark-text fs-1 text-muted"
                                    ></i>

                                    <p class="fw-semibold mt-3 mb-1">
                                        Nenhuma ficha catalográfica encontrada
                                    </p>

                                    <p class="text-muted mb-3">
                                        Você ainda não possui fichas catalográficas geradas.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


        </div>

    </div>


    @if(method_exists($fichas, 'links'))

        <div class="mt-4">
            {{ $fichas->links() }}
        </div>

    @endif

</div>

@endsection
