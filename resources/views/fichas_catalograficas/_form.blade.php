@php
    $tipoSelecionadoId = (string) old('tipo_ficha_catalografica_id', $ficha->tipo_ficha_catalografica_id ?? '');
    $tipoSelecionado = $tipos_fichas_catalograficas->first(fn ($tipo) => (string) $tipo->id === $tipoSelecionadoId);
    $tipoSelecionadoSlug = $tipoSelecionado?->slug ?? '';
    $areaSelecionada = (string) old(
        'area_conhecimento',
        isset($ficha)
            ? (\App\Enums\AreaConhecimento::fromCodigoCDD($ficha->area_conhecimento)?->value ?? $ficha->area_conhecimento ?? '')
            : ''
    );
    $possuiAnexoExistente = isset($ficha) && !empty($ficha->anexo);
    $produtosEducacionais = [
        'acervo' => 'Acervo',
        'ativos_propriedade' => 'Ativos de Propriedade (Ex: Patente, Marca e etc.)',
        'base_dados' => 'Base de dados técnico-científica',
        'carta' => 'Carta, mapa ou similar',
        'cultivar' => 'Cultivar',
        'curso_formacao' => 'Curso para Formação Profissional',
        'empresa_social' => 'Empresa ou Organização Social Inovadora',
        'evento' => 'Evento Organizado',
        'manual' => 'Manual/Protocolo',
        'material_didatico' => 'Material Didático',
        'norma' => 'Norma ou Marco Regulatório',
        'processo' => 'Processo / Tecnologia e Produto / Material não patenteáveis',
        'produto_bibliografico' => 'Produto Bibliográfico',
        'produto_comunicacao' => 'Produto de Comunicação',
        'produto_editoracao' => 'Produto de Editoração',
        'produto_sigilo' => 'Produtos/Processos em Sigilo',
        'relatorio' => 'Relatório técnico conclusivo',
        'software' => 'Software/Aplicativo (Programa de computador)',
        'taxonomia' => 'Taxonomias, Ontologias e Tesauros',
        'tecnologia_social' => 'Tecnologia Social',
        'traducao' => 'Tradução',
    ];
    $produtoSelecionado = old('produto', $ficha->programaEducacional->produto ?? '');
@endphp

<!-- Dados do Trabalho -->
<div class="col-md-12 corpoFicha shadow my-4">
    <div class="row">
        <div class="col-md-12 cabecalho py-2">
            <span class="tituloCabecalho">Dados do Trabalho</span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 py-2">
            <div class="form-group">
                <label for="tipo_ficha_catalografica_id" class="form-label">
                    Tipo de Ficha Catalográfica:
                    <span style="color: red">*</span>
                </label>

                <select
                    class="form-control @error('tipo_ficha_catalografica_id') is-invalid @enderror"
                    name="tipo_ficha_catalografica_id"
                    id="tipo_ficha_catalografica_id"
                    required
                >
                    <option
                        value=""
                        disabled
                        @if ($tipoSelecionadoId === '') selected @endif
                    >
                        Selecione o tipo de ficha catalográfica
                    </option>

                    @foreach ($tipos_fichas_catalograficas as $tipo)
                        <option
                            value="{{ $tipo->id }}"
                            data-tipo="{{ $tipo->nome }}"
                            data-slug="{{ $tipo->slug }}"
                            @if ($tipoSelecionadoId === (string) $tipo->id) selected @endif
                        >
                            {{ $tipo->nome }}
                        </option>
                    @endforeach
                </select>

                @error('tipo_ficha_catalografica_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="autor_nome">
                    Nome:
                    <span style="color: red">*</span>
                </label>

                <input
                    type="text"
                    class="form-control @error('autor_nome') is-invalid @enderror"
                    id="autor_nome"
                    name="autor_nome"
                    placeholder="Digite o nome do Autor"
                    value="{{ old('autor_nome', $ficha->autor_nome ?? '') }}"
                    required
                >

                @error('autor_nome')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="autor_sobrenome">
                    Sobrenome:
                    <span style="color: red">*</span>
                </label>

                <input
                    type="text"
                    class="form-control @error('autor_sobrenome') is-invalid @enderror"
                    id="autor_sobrenome"
                    name="autor_sobrenome"
                    placeholder="Digite o sobrenome do Autor"
                    value="{{ old('autor_sobrenome', $ficha->autor_sobrenome ?? '') }}"
                    required
                >

                @error('autor_sobrenome')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="titulo">
                    Título:
                    <span style="color: red">*</span>
                </label>

                <div class="mb-2">
                    <textarea
                        class="editor-ckeditor1 @error('titulo') is-invalid @enderror"
                        id="titulo"
                        name="titulo"
                    >{{ old('titulo', $ficha->titulo ?? '') }}</textarea>

                    @error('titulo')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="subtitulo">Subtítulo:</label>

                <div class="mb-2">
                    <textarea
                        class="editor-ckeditor2 @error('subtitulo') is-invalid @enderror"
                        id="subtitulo"
                        name="subtitulo"
                    >{{ old('subtitulo', $ficha->subtitulo ?? '') }}</textarea>

                    @error('subtitulo')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="local">
                    Local:
                    <span style="color: red">*</span>
                </label>

                <input
                    type="text"
                    class="form-control @error('local') is-invalid @enderror"
                    id="local"
                    name="local"
                    placeholder="Digite o Local"
                    value="{{ old('local', $ficha->local ?? '') }}"
                    required
                >

                @error('local')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="row justify-content-between">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="ano">
                            Ano:
                            <span style="color: red">*</span>
                        </label>

                        <input
                            class="form-control @error('ano') is-invalid @enderror"
                            type="number"
                            min="1900"
                            max="2099"
                            step="1"
                            name="ano"
                            id="ano"
                            placeholder="Digite o Ano"
                            value="{{ old('ano', $ficha->ano ?? date('Y')) }}"
                            required
                        >

                        @error('ano')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="folhas">
                            Folhas:
                            <span style="color: red">*</span>
                        </label>

                        <input
                            type="number"
                            class="form-control @error('folhas') is-invalid @enderror"
                            id="folhas"
                            name="folhas"
                            min="1"
                            placeholder="Quantidade de Folhas"
                            value="{{ old('folhas', $ficha->folhas ?? '') }}"
                            required
                        >

                        @error('folhas')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="ilustracao">
                            Ilustração:
                            <span style="color: red">*</span>
                        </label>

                        <select
                            class="form-control @error('ilustracao') is-invalid @enderror"
                            id="ilustracao"
                            name="ilustracao"
                            required
                        >
                            <option
                                value=""
                                disabled
                                @if (old('ilustracao', $ficha->ilustracao ?? '') === '') selected @endif
                            >
                                Selecione o tipo de ilustração
                            </option>
                            <option
                                value="colorido"
                                @if (old('ilustracao', $ficha->ilustracao ?? '') === 'colorido') selected @endif
                            >
                                Colorido
                            </option>

                            <option
                                value="preto_branco"
                                @if (old('ilustracao', $ficha->ilustracao ?? '') === 'preto_branco') selected @endif
                            >
                                Preto e Branco
                            </option>

                            <option
                                value="nao_possui"
                                @if (old('ilustracao', $ficha->ilustracao ?? '') === 'nao_possui') selected @endif
                            >
                                Não Possui
                            </option>
                        </select>

                        @error('ilustracao')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row justify-content-between">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inclui_anexo">
                            Inclui Anexo?
                            <span style="color: red">*</span>
                        </label>

                        <select
                            class="form-control @error('inclui_anexo') is-invalid @enderror"
                            id="inclui_anexo"
                            name="inclui_anexo"
                        >
                            <option
                                value="0"
                                @if (!old('inclui_anexo', $ficha->inclui_anexo ?? false)) selected @endif
                            >
                                Não
                            </option>

                            <option
                                value="1"
                                @if (old('inclui_anexo', $ficha->inclui_anexo ?? false)) selected @endif
                            >
                                Sim
                            </option>
                        </select>

                        @error('inclui_anexo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inclui_apendice">
                            Inclui Apêndice?
                            <span style="color: red">*</span>
                        </label>

                        <select
                            class="form-control @error('inclui_apendice') is-invalid @enderror"
                            id="inclui_apendice"
                            name="inclui_apendice"
                        >
                            <option
                                value="0"
                                @if (!old('inclui_apendice', $ficha->inclui_apendice ?? false)) selected @endif
                            >
                                Não
                            </option>

                            <option
                                value="1"
                                @if (old('inclui_apendice', $ficha->inclui_apendice ?? false)) selected @endif
                            >
                                Sim
                            </option>
                        </select>

                        @error('inclui_apendice')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="anexo">
                    Anexar os elementos pré-textuais:
                    @if (!$possuiAnexoExistente)
                        <span style="color: red">*</span>
                    @endif
                </label>

                <br>

                <input
                    type="file"
                    class="@error('anexo') is-invalid @enderror"
                    id="anexo"
                    name="anexo"
                    accept="application/pdf,.pdf"
                    style="margin-bottom: 0;"
                    data-has-existing="{{ $possuiAnexoExistente ? '1' : '0' }}"
                    @if (!$possuiAnexoExistente) required @endif
                >

                <br>

                <span
                    id="tipoAnexo"
                    style="font-size: small; color: gray; margin-top: 0; margin-bottom: 10px;"
                >
                    @if ($possuiAnexoExistente)
                        Anexo atual salvo ({{ $ficha->anexo }}). Envie um novo PDF apenas se desejar substituí-lo (máximo: 10 MB).
                    @else
                        Tipo permitido: PDF. Tamanho máximo: 10 MB.
                    @endif
                </span>

                @error('anexo')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
</div>

<!-- Informações acadêmicas -->
<div class="col-md-12 corpoFicha shadow my-4">
    <div class="row">
        <div class="col-md-12 cabecalho py-2">
            <span class="tituloCabecalho">Informações acadêmicas</span>
        </div>
    </div>

    <div class="row py-2">
        <div class="col-md-6 form-group">
            <label for="nome_orientador" class="form-label">
                Nome do Orientador:
                <span style="color: red">*</span>
            </label>

            <input
                type="text"
                class="form-control @error('nome_orientador') is-invalid @enderror"
                id="nome_orientador"
                name="nome_orientador"
                placeholder="Digite o nome do orientador"
                value="{{ old('nome_orientador', $ficha->nome_orientador ?? '') }}"
                required
            >

            @error('nome_orientador')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="col-md-6 form-group">
            <label for="sobrenome_orientador" class="form-label">
                Sobrenome do Orientador:
                <span style="color: red">*</span>
            </label>

            <input
                type="text"
                class="form-control @error('sobrenome_orientador') is-invalid @enderror"
                id="sobrenome_orientador"
                name="sobrenome_orientador"
                placeholder="Digite o Sobrenome do orientador"
                value="{{ old('sobrenome_orientador', $ficha->sobrenome_orientador ?? '') }}"
                required
            >

            @error('sobrenome_orientador')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="col-md-6 form-group">
            <label for="nome_coorientador" class="form-label">
                Nome do Coorientador:
            </label>

            <input
                type="text"
                class="form-control @error('nome_coorientador') is-invalid @enderror"
                id="nome_coorientador"
                name="nome_coorientador"
                placeholder="Digite o Nome do Coorientador"
                value="{{ old('nome_coorientador', $ficha->nome_coorientador ?? '') }}"
            >

            @error('nome_coorientador')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="col-md-6 form-group">
            <label for="sobrenome_coorientador" class="form-label">
                Sobrenome do Coorientador:
            </label>

            <input
                type="text"
                class="form-control @error('sobrenome_coorientador') is-invalid @enderror"
                id="sobrenome_coorientador"
                name="sobrenome_coorientador"
                placeholder="Digite o Sobrenome do Coorientador"
                value="{{ old('sobrenome_coorientador', $ficha->sobrenome_coorientador ?? '') }}"
            >

            @error('sobrenome_coorientador')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="col-md-6 form-group">
            <label for="nome_segundo_coorientador" class="form-label">
                Nome do Segundo Coorientador:
            </label>

            <input
                type="text"
                class="form-control @error('nome_segundo_coorientador') is-invalid @enderror"
                id="nome_segundo_coorientador"
                name="nome_segundo_coorientador"
                placeholder="Digite o Nome do Segundo Coorientador"
                value="{{ old('nome_segundo_coorientador', $ficha->nome_segundo_coorientador ?? '') }}"
            >

            @error('nome_segundo_coorientador')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="col-md-6 form-group">
            <label for="sobrenome_segundo_coorientador" class="form-label">
                Sobrenome do Segundo Coorientador:
            </label>

            <input
                type="text"
                class="form-control @error('sobrenome_segundo_coorientador') is-invalid @enderror"
                id="sobrenome_segundo_coorientador"
                name="sobrenome_segundo_coorientador"
                placeholder="Digite o Sobrenome do Segundo Coorientador"
                value="{{ old('sobrenome_segundo_coorientador', $ficha->sobrenome_segundo_coorientador ?? '') }}"
            >

            @error('sobrenome_segundo_coorientador')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- Monografia -->
        <div
            id="campos-monografia"
            class="col-md-12 campos-documento form-group {{ $tipoSelecionadoSlug === 'monografia' ? '' : 'd-none' }}"
        >
            <label for="tipo_curso" class="form-label">
                Selecione o Tipo do seu Curso:
                <span style="color: red">*</span>
            </label>

            <select
                class="form-control @error('tipo_curso') is-invalid @enderror"
                aria-label="Selecione o tipo do curso"
                name="tipo_curso"
                id="tipo_curso"
                required
                @if ($tipoSelecionadoSlug !== 'monografia') disabled @endif
            >
                <option value="" disabled @if (empty(old('tipo_curso', $ficha->monografia->tipo_curso ?? ''))) selected @endif>
                    Selecione o tipo do curso
                </option>
                <option
                    value="especializacao"
                    @if (old('tipo_curso', $ficha->monografia->tipo_curso ?? '') === 'especializacao') selected @endif
                >
                    Especialização
                </option>

                <option
                    value="graduacao"
                    @if (old('tipo_curso', $ficha->monografia->tipo_curso ?? '') === 'graduacao') selected @endif
                >
                    Graduação
                </option>

                <option
                    value="mestrado"
                    @if (old('tipo_curso', $ficha->monografia->tipo_curso ?? '') === 'mestrado') selected @endif
                >
                    Mestrado
                </option>
            </select>

            @error('tipo_curso')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- Dissertação -->
        <div
            id="campos-dissertacao"
            class="col-md-12 campos-documento form-group {{ $tipoSelecionadoSlug === 'dissertacao' ? '' : 'd-none' }}"
        >
            <label for="programa_dissertacao" class="form-label">
                Nome do Programa de Pós-Graduação:
                <span style="color: red">*</span>
            </label>

            <input
                type="text"
                class="form-control @error('programa') is-invalid @enderror"
                id="programa_dissertacao"
                name="programa"
                placeholder="Digite o Nome do Programa"
                value="{{ old('programa', $ficha->dissertacao->programa ?? '') }}"
                required
                @if ($tipoSelecionadoSlug !== 'dissertacao') disabled @endif
            >

            @error('programa')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- Tese -->
        <div
            id="campos-tese"
            class="col-md-12 campos-documento form-group {{ $tipoSelecionadoSlug === 'tese' ? '' : 'd-none' }}"
        >
            <label for="programa_tese" class="form-label">
                Nome do Programa de Pós-Graduação:
                <span style="color: red">*</span>
            </label>

            <input
                type="text"
                class="form-control @error('programa') is-invalid @enderror"
                id="programa_tese"
                name="programa"
                placeholder="Digite o Nome do Programa"
                value="{{ old('programa', $ficha->tese->programa ?? '') }}"
                required
                @if ($tipoSelecionadoSlug !== 'tese') disabled @endif
            >

            @error('programa')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- Produto Educacional -->
        <div
            id="campos-produto-educacional"
            class="col-md-12 campos-documento {{ in_array($tipoSelecionadoSlug, ['produto-educacional', 'programa_educacional'], true) ? '' : 'd-none' }}"
        >
            <div class="form-group">
                <label for="programa_produto_educacional" class="form-label">
                    Nome do Programa de Pós-Graduação:
                    <span style="color: red">*</span>
                </label>

                <input
                    type="text"
                    class="form-control @error('programa') is-invalid @enderror"
                    id="programa_produto_educacional"
                    name="programa"
                    placeholder="Digite o Nome do Programa"
                    value="{{ old('programa', $ficha->programaEducacional->programa ?? '') }}"
                    required
                    @if (!in_array($tipoSelecionadoSlug, ['produto-educacional', 'programa_educacional'], true)) disabled @endif
                >

                @error('programa')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="produto" class="form-label">
                    Selecione o Tipo do Produto:
                    <span style="color: red">*</span>
                </label>

                <select
                    class="form-control @error('produto') is-invalid @enderror"
                    aria-label="Selecione o tipo do produto"
                    name="produto"
                    id="produto"
                    required
                    @if (!in_array($tipoSelecionadoSlug, ['produto-educacional', 'programa_educacional'], true)) disabled @endif
                >
                    <option value="" disabled @if (empty($produtoSelecionado)) selected @endif>
                        Selecione o tipo do produto
                    </option>
                    @foreach ($produtosEducacionais as $valorProduto => $labelProduto)
                        <option
                            value="{{ $valorProduto }}"
                            @if ($produtoSelecionado === $valorProduto) selected @endif
                        >
                            {{ $labelProduto }}
                        </option>
                    @endforeach
                </select>

                @error('produto')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
</div>

<!-- Identificação Catalográfica (Código de Cutter → Área do Conhecimento) -->
<div class="col-md-12 corpoFicha shadow my-4">
    <div class="row">
        <div class="col-md-12 cabecalho py-2">
            <span class="tituloCabecalho">Identificação Catalográfica</span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 py-2 textoFicha">
            <div class="form-group">
                <label for="cutter" class="form-label">
                    Código de Cutter:
                    <span style="color: red">*</span>
                </label>

                <div class="input-group">
                    <input
                        type="text"
                        class="form-control @error('cutter') is-invalid @enderror"
                        id="cutter"
                        name="cutter"
                        value="{{ old('cutter', $ficha->cutter ?? '') }}"
                        placeholder="Digite o código de Cutter (ex.: S586)"
                        required
                    >

                    <a
                        href="https://www.tabelacutter.com/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn"
                        style="background-color: var(--biblioteca); color: white;"
                    >
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        Tabela Cutter
                    </a>

                    @error('cutter')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <small class="form-text text-muted">
                    Consulte o código do seu sobrenome na Tabela Cutter.
                </small>
            </div>

            <div class="form-group">
                <label for="area_conhecimento" class="form-label">
                    Área do Conhecimento:
                    <span style="color: red">*</span>
                </label>

                <select
                    class="form-control @error('area_conhecimento') is-invalid @enderror"
                    id="area_conhecimento"
                    name="area_conhecimento"
                    required
                >
                    <option value="" @if ($areaSelecionada === '') selected @endif>
                        Selecione a área do conhecimento
                    </option>

                    @foreach (\App\Enums\AreaConhecimento::cases() as $area)
                        <option
                            value="{{ $area->value }}"
                            @if ($areaSelecionada === (string) $area->value) selected @endif
                        >
                            {{ $area->descricaoCompleta() }}
                        </option>
                    @endforeach
                </select>

                @error('area_conhecimento')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
</div>

<!-- Palavras-chave -->
<div class="col-md-12 corpoFicha shadow my-4">
    <div class="row">
        <div class="col-md-12 cabecalho py-2">
            <span class="tituloCabecalho">Palavras-chave</span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 py-2 textoFicha">
            <div class="form-group">
                <label for="primeira" class="form-label">
                    Primeira Palavra:
                    <span style="color: red">*</span>
                </label>

                <input
                    type="text"
                    class="form-control @error('primeira_chave') is-invalid @enderror"
                    id="primeira"
                    name="primeira_chave"
                    placeholder="1. Palavras-chave"
                    value="{{ old('primeira_chave', $ficha->palavrasChave[0]->palavra ?? '') }}"
                    required
                >

                @error('primeira_chave')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="segunda">
                    Segunda Palavra:
                    <span style="color: red">*</span>
                </label>

                <input
                    type="text"
                    class="form-control @error('segunda_chave') is-invalid @enderror"
                    id="segunda"
                    name="segunda_chave"
                    placeholder="2. Palavras-chave"
                    value="{{ old('segunda_chave', $ficha->palavrasChave[1]->palavra ?? '') }}"
                    required
                >

                @error('segunda_chave')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="terceira">
                    Terceira Palavra:
                    <span style="color: red">*</span>
                </label>

                <input
                    type="text"
                    class="form-control @error('terceira_chave') is-invalid @enderror"
                    id="terceira"
                    name="terceira_chave"
                    placeholder="3. Palavras-chave"
                    value="{{ old('terceira_chave', $ficha->palavrasChave[2]->palavra ?? '') }}"
                    required
                >

                @error('terceira_chave')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="quarta">Quarta Palavra:</label>

                <input
                    type="text"
                    class="form-control @error('quarta_chave') is-invalid @enderror"
                    id="quarta"
                    name="quarta_chave"
                    placeholder="4. Palavras-chave"
                    value="{{ old('quarta_chave', $ficha->palavrasChave[3]->palavra ?? '') }}"
                >

                @error('quarta_chave')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="quinta">Quinta Palavra:</label>

                <input
                    type="text"
                    class="form-control @error('quinta_chave') is-invalid @enderror"
                    id="quinta"
                    name="quinta_chave"
                    placeholder="5. Palavras-chave"
                    value="{{ old('quinta_chave', $ficha->palavrasChave[4]->palavra ?? '') }}"
                >

                @error('quinta_chave')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
</div>

<div style="display: flex; text-align: justify; text-justify: inter-word;">
    <div>
        <input
            type="checkbox"
            class="@error('checkBoxConfirma') is-invalid @enderror"
            id="checkBoxConfirma"
            name="checkBoxConfirma"
            value="1"
            @if (old('checkBoxConfirma') || isset($ficha)) checked @endif
            onchange="verificaCheckBoxConfirma()"
        >
    </div>

    &nbsp;

    <div>
        <label for="checkBoxConfirma">
            {{ __('messages.autorizo') }}
            <span style="color: red">*</span>
        </label>

        @error('checkBoxConfirma')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<script>
    function verificaCheckBoxConfirma() {
        const checkbox = document.getElementById('checkBoxConfirma');
        const botao = document.getElementById('botaoEnviar');

        if (checkbox && botao) {
            botao.disabled = !checkbox.checked;
        }
    }

    $(document).ready(function () {
        function atualizarCamposDocumento() {
            $('.campos-documento')
                .addClass('d-none')
                .find('input, select, textarea')
                .prop('disabled', true);

            const slug = $('#tipo_ficha_catalografica_id option:selected').data('slug');

            const blocos = {
                'dissertacao': '#campos-dissertacao',
                'monografia': '#campos-monografia',
                'tese': '#campos-tese',
                'produto-educacional': '#campos-produto-educacional',
                'programa_educacional': '#campos-produto-educacional'
            };

            const bloco = blocos[slug];

            if (bloco) {
                $(bloco)
                    .removeClass('d-none')
                    .find('input, select, textarea')
                    .prop('disabled', false);
            }
        }

        function validarAnexo(exibirAlerta) {
            const input = document.getElementById('anexo');
            const arquivo = input.files[0];
            const possuiAnexoExistente = input.dataset.hasExisting === '1';

            input.setCustomValidity('');

            if (!arquivo) {
                $('#tipoAnexo').css('color', 'gray');
                return possuiAnexoExistente;
            }

            const extensao = arquivo.name.split('.').pop().toLowerCase();
            let mensagem = '';

            if (extensao !== 'pdf') {
                mensagem = 'Os elementos pré-textuais devem estar no formato PDF.';
            } else if (arquivo.size > 10 * 1024 * 1024) {
                mensagem = 'Os elementos pré-textuais devem ter tamanho máximo de 10 MB.';
            }

            input.setCustomValidity(mensagem);

            $('#tipoAnexo').css('color', mensagem ? 'red' : 'green');

            if (mensagem && exibirAlerta) {
                alert(mensagem);
            }

            return mensagem === '';
        }

        $('#tipo_ficha_catalografica_id').on('change', atualizarCamposDocumento);

        atualizarCamposDocumento();
        verificaCheckBoxConfirma();

        $('#anexo').on('change', function () {
            validarAnexo(true);
        });

        $('#formRequisicao').on('submit', function (event) {
            if (!validarAnexo(true)) {
                event.preventDefault();
                document.getElementById('anexo').reportValidity();
            }
        });
    });
</script>
