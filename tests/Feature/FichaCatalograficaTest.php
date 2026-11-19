<?php

namespace Tests\Feature;

use App\Enums\AreaConhecimento;
use App\Models\Aluno;
use App\Models\FichaCatalografica;
use App\Models\Perfil;
use App\Models\TipoFichaCatalografica;
use App\Models\Unidade;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FichaCatalograficaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ThrottleRequests::class);

        Storage::fake(config('filesystems.default'));

        TipoFichaCatalografica::query()->forceCreate([
            'id' => 1,
            'nome' => 'Dissertação',
            'slug' => 'dissertacao',
        ]);

        TipoFichaCatalografica::query()->forceCreate([
            'id' => 2,
            'nome' => 'Monografia',
            'slug' => 'monografia',
        ]);

        TipoFichaCatalografica::query()->forceCreate([
            'id' => 3,
            'nome' => 'Produto Educacional',
            'slug' => 'produto-educacional',
        ]);

        TipoFichaCatalografica::query()->forceCreate([
            'id' => 4,
            'nome' => 'Tese',
            'slug' => 'tese',
        ]);
    }

    private function criarAluno(): array
    {
        $user = User::factory()->create([
            'tipo' => 'aluno',
            'active' => true,
        ]);

        $aluno = Aluno::factory()->create([
            'user_id' => $user->id,
        ]);

        $unidade = Unidade::factory()->create([
            'nome' => 'UFAPE - Campus Garanhuns',
        ]);

        $perfil = Perfil::factory()->create([
            'aluno_id' => $aluno->id,
            'unidade_id' => $unidade->id,
            'default' => 'Ciência da Computação',
            'valor' => true,
        ]);

        return [
            'user' => $user,
            'aluno' => $aluno,
            'perfil' => $perfil,
            'unidade' => $unidade,
        ];
    }

    private function criarBibliotecario(): User
    {
        return User::factory()->create([
            'tipo' => 'bibliotecario',
            'active' => true,
        ]);
    }

    private function dadosBase(): array
    {
        return [
            'tipo_ficha_catalografica_id' => 2,
            'autor_nome' => 'João',
            'autor_sobrenome' => 'Silva',
            'cutter' => 'S586',
            'area_conhecimento' => '004',
            'titulo' => 'Sistema de Gestão Acadêmica',
            'subtitulo' => 'Uma abordagem utilizando Laravel',
            'local' => 'Garanhuns',
            'ano' => 2026,
            'folhas' => 80,
            'ilustracao' => 'colorido',
            'inclui_anexo' => 0,
            'inclui_apendice' => 0,
            'nome_orientador' => 'Maria',
            'sobrenome_orientador' => 'Souza',
            'nome_coorientador' => null,
            'sobrenome_coorientador' => null,
            'nome_segundo_coorientador' => null,
            'sobrenome_segundo_coorientador' => null,
            'tipo_curso' => 'graduacao',
            'programa' => 'Programa de Pós-Graduação em Ciência da Computação',
            'produto' => 'software',
            'primeira_chave' => 'Laravel',
            'segunda_chave' => 'Sistema',
            'terceira_chave' => 'Gestão',
            'quarta_chave' => null,
            'quinta_chave' => null,
            'anexo' => UploadedFile::fake()->create(
                'trabalho.pdf',
                500,
                'application/pdf'
            ),
        ];
    }

    public function test_formulario_criacao_exibe_campos_na_ordem_e_sem_classificacao(): void
    {
        $contexto = $this->criarAluno();

        $response = $this
            ->actingAs($contexto['user'])
            ->get(route('fichas.create'));

        $response->assertOk();

        $html = $response->getContent();

        $response->assertSee('Código de Cutter');
        $response->assertSee('Consulte o código do seu sobrenome');
        $response->assertSee('Tabela Cutter');
        $response->assertSee('Área do Conhecimento');
        $response->assertSee('004 - Ciência da computação');
        $response->assertSee('370 - Educação');
        $response->assertSee('636.089 - Medicina veterinária');
        $response->assertSee('B869 - Literatura brasileira');
        $response->assertSee('Dissertação');
        $response->assertSee('Monografia');
        $response->assertSee('Produto Educacional');
        $response->assertSee('Tese');
        $response->assertDontSee('Classificação');

        $posCutter = strpos($html, 'id="cutter"');
        $posTabelaCutter = strpos($html, 'Tabela Cutter');
        $posArea = strpos($html, 'id="area_conhecimento"');
        $posPalavras = strpos($html, 'Palavras-chave');

        $this->assertNotFalse($posCutter);
        $this->assertNotFalse($posTabelaCutter);
        $this->assertNotFalse($posArea);
        $this->assertNotFalse($posPalavras);

        $this->assertLessThan($posTabelaCutter, $posCutter);
        $this->assertLessThan($posArea, $posTabelaCutter);
        $this->assertLessThan($posPalavras, $posArea);
    }

    public function test_aluno_pode_criar_monografia_sem_notificar_biblioteca(): void
    {
        Mail::fake();
        Notification::fake();

        $contexto = $this->criarAluno();

        $response = $this
            ->actingAs($contexto['user'])
            ->post(route('fichas.store'), $this->dadosBase());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('fichas.index'));

        Mail::assertNothingSent();
        Notification::assertNothingSent();

        $this->assertDatabaseHas('ficha_catalograficas', [
            'aluno_id' => $contexto['aluno']->id,
            'tipo_ficha_catalografica_id' => 2,
            'cutter' => 'S586',
            'area_conhecimento' => '004',
            'autor_nome' => 'João',
            'autor_sobrenome' => 'Silva',
            'titulo' => 'Sistema de Gestão Acadêmica',
            'nome_orientador' => 'Maria',
            'sobrenome_orientador' => 'Souza',
        ]);

        $ficha = FichaCatalografica::where('aluno_id', $contexto['aluno']->id)->firstOrFail();

        $this->assertNotNull($ficha->monografia);
        $this->assertNull($ficha->dissertacao);
        $this->assertNull($ficha->tese);
        $this->assertNull($ficha->programaEducacional);
        $this->assertEquals('Ciência da Computação', $ficha->monografia->curso);
        $this->assertEquals('graduacao', $ficha->monografia->tipo_curso);
        $this->assertEquals('UFAPE - Campus Garanhuns', $ficha->monografia->campus);
    }

    public function test_aluno_pode_criar_dissertacao(): void
    {
        $contexto = $this->criarAluno();

        $dados = $this->dadosBase();
        $dados['tipo_ficha_catalografica_id'] = 1;
        $dados['programa'] = 'Mestrado em Ciência da Computação';

        $response = $this
            ->actingAs($contexto['user'])
            ->post(route('fichas.store'), $dados);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('fichas.index'));

        $ficha = FichaCatalografica::where('aluno_id', $contexto['aluno']->id)->firstOrFail();

        $this->assertEquals(1, (int) $ficha->tipo_ficha_catalografica_id);
        $this->assertNotNull($ficha->dissertacao);
        $this->assertNull($ficha->monografia);
        $this->assertEquals('Mestrado em Ciência da Computação', $ficha->dissertacao->programa);
        $this->assertEquals('UFAPE - Campus Garanhuns', $ficha->dissertacao->campus);
    }

    public function test_aluno_pode_criar_produto_educacional(): void
    {
        $contexto = $this->criarAluno();

        $dados = $this->dadosBase();
        $dados['tipo_ficha_catalografica_id'] = 3;
        $dados['programa'] = 'Mestrado Profissional em Letras';
        $dados['produto'] = 'material_didatico';
        $dados['area_conhecimento'] = '370';

        $response = $this
            ->actingAs($contexto['user'])
            ->post(route('fichas.store'), $dados);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('fichas.index'));

        $ficha = FichaCatalografica::where('aluno_id', $contexto['aluno']->id)->firstOrFail();

        $this->assertEquals(3, (int) $ficha->tipo_ficha_catalografica_id);
        $this->assertNotNull($ficha->programaEducacional);
        $this->assertNull($ficha->monografia);
        $this->assertEquals('Mestrado Profissional em Letras', $ficha->programaEducacional->programa);
        $this->assertEquals('material_didatico', $ficha->programaEducacional->produto);
        $this->assertEquals('UFAPE - Campus Garanhuns', $ficha->programaEducacional->campus);
    }

    public function test_aluno_pode_criar_tese(): void
    {
        $contexto = $this->criarAluno();

        $dados = $this->dadosBase();
        $dados['tipo_ficha_catalografica_id'] = 4;
        $dados['programa'] = 'Doutorado em Sanidade e Reprodução Animal';
        $dados['area_conhecimento'] = '636.089';

        $response = $this
            ->actingAs($contexto['user'])
            ->post(route('fichas.store'), $dados);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('fichas.index'));

        $ficha = FichaCatalografica::where('aluno_id', $contexto['aluno']->id)->firstOrFail();

        $this->assertEquals(4, (int) $ficha->tipo_ficha_catalografica_id);
        $this->assertNotNull($ficha->tese);
        $this->assertNull($ficha->monografia);
        $this->assertEquals('Doutorado em Sanidade e Reprodução Animal', $ficha->tese->programa);
    }

    public function test_gera_pdf_sem_bibliotecario_para_todos_os_tipos(): void
    {
        $cenarios = [
            ['tipo_id' => 1, 'area' => '004', 'esperado' => 'Dissertação (Mestrado)'],
            ['tipo_id' => 2, 'area' => '370', 'esperado' => 'Monografia'],
            ['tipo_id' => 3, 'area' => 'B869', 'esperado' => 'Produto técnico/tecnológico'],
            ['tipo_id' => 4, 'area' => '636.089', 'esperado' => 'Tese (Doutorado)'],
        ];

        foreach ($cenarios as $cenario) {
            $contexto = $this->criarAluno();

            $dados = $this->dadosBase();
            $dados['tipo_ficha_catalografica_id'] = $cenario['tipo_id'];
            $dados['area_conhecimento'] = $cenario['area'];
            $dados['nome_coorientador'] = 'Carlos';
            $dados['sobrenome_coorientador'] = 'Pereira';

            $this->actingAs($contexto['user'])
                ->post(route('fichas.store'), $dados)
                ->assertSessionHasNoErrors();

            $ficha = FichaCatalografica::where('aluno_id', $contexto['aluno']->id)->firstOrFail();

            // Verifica a rota de download de PDF (sem nenhum bibliotecario_id)
            $pdfResponse = $this
                ->actingAs($contexto['user'])
                ->get(route('fichas.gerar', $ficha->id));

            $pdfResponse->assertOk();
            $pdfResponse->assertHeader('content-type', 'application/pdf');

            // Verifica o HTML renderizado para o PDF
            $tipo_ficha = $ficha->tipoFichaCatalografica->slug;
            $palavras = $ficha->palavrasChave()->orderBy('id')->get();
            $unidade = $contexto['unidade'];
            $documento = match ($tipo_ficha) {
                'monografia' => $ficha->monografia,
                'tese' => $ficha->tese,
                'produto-educacional' => $ficha->programaEducacional,
                default => $ficha->dissertacao,
            };

            $html = view('telas_bibliotecario.gerar_ficha', compact('ficha', 'palavras', 'tipo_ficha', 'documento', 'unidade'))->render();

            $this->assertStringContainsString(
                'Ficha de identificação da obra elaborada pelo autor, através do programa de geração automática da UFAPE',
                $html
            );
            $this->assertStringNotContainsString('Elaborado pelo(a) bibliotecário(a)', $html);
            $this->assertStringNotContainsString('CRB', $html);
            $this->assertStringContainsString($ficha->cutter, $html);
            $this->assertStringContainsString('CDD ' . $cenario['area'], $html);
            $this->assertStringContainsString('Silva, João', $html);
            $this->assertStringContainsString('Orientador(a): Maria Souza.', $html);
            $this->assertStringContainsString('Coorientador(a): Carlos Pereira.', $html);
            $this->assertStringContainsString('1. Laravel.', $html);
            $this->assertStringContainsString($cenario['esperado'], $html);
        }
    }

    public function test_cria_palavras_chave_da_ficha(): void
    {
        $contexto = $this->criarAluno();

        $response = $this
            ->actingAs($contexto['user'])
            ->post(route('fichas.store'), $this->dadosBase());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('fichas.index'));

        $ficha = FichaCatalografica::where('aluno_id', $contexto['aluno']->id)->firstOrFail();

        $this->assertCount(3, $ficha->palavrasChave);

        $this->assertEqualsCanonicalizing(
            ['Laravel', 'Sistema', 'Gestão'],
            $ficha->palavrasChave->pluck('palavra')->all()
        );
    }

    public function test_nao_cria_palavras_chave_vazias(): void
    {
        $contexto = $this->criarAluno();

        $dados = $this->dadosBase();
        $dados['quarta_chave'] = '';
        $dados['quinta_chave'] = null;

        $response = $this
            ->actingAs($contexto['user'])
            ->post(route('fichas.store'), $dados);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('fichas.index'));

        $ficha = FichaCatalografica::where('aluno_id', $contexto['aluno']->id)->firstOrFail();

        $palavras = $ficha->palavrasChave->pluck('palavra')->all();

        $this->assertCount(3, $palavras);

        foreach ($palavras as $palavra) {
            $this->assertNotNull($palavra);
            $this->assertNotSame('', trim($palavra));
        }
    }

    public function test_aluno_pode_atualizar_monografia_sem_reenviar_anexo(): void
    {
        $contexto = $this->criarAluno();

        $this->actingAs($contexto['user'])
            ->post(route('fichas.store'), $this->dadosBase())
            ->assertSessionHasNoErrors();

        $ficha = FichaCatalografica::where('aluno_id', $contexto['aluno']->id)->firstOrFail();
        $anexoOriginal = $ficha->anexo;

        $dados = $this->dadosBase();
        unset($dados['anexo']);
        $dados['titulo'] = 'Título atualizado';
        $dados['subtitulo'] = 'Novo subtítulo';
        $dados['tipo_curso'] = 'especializacao';

        $response = $this
            ->actingAs($contexto['user'])
            ->put(route('fichas.update', $ficha->id), $dados);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('fichas.index'));

        $ficha->refresh();

        $this->assertEquals('Título atualizado', $ficha->titulo);
        $this->assertEquals('Novo subtítulo', $ficha->subtitulo);
        $this->assertEquals($anexoOriginal, $ficha->anexo);
        $this->assertNotNull($ficha->monografia);
        $this->assertEquals('especializacao', $ficha->monografia->tipo_curso);
    }

    public function test_pode_mudar_tipo_entre_todos_os_quatro_tipos(): void
    {
        $contexto = $this->criarAluno();

        $this->actingAs($contexto['user'])
            ->post(route('fichas.store'), $this->dadosBase())
            ->assertSessionHasNoErrors();

        $ficha = FichaCatalografica::where('aluno_id', $contexto['aluno']->id)->firstOrFail();
        $this->assertNotNull($ficha->monografia);

        // Monografia -> Dissertação
        $dados = $this->dadosBase();
        unset($dados['anexo']);
        $dados['tipo_ficha_catalografica_id'] = 1;
        $dados['programa'] = 'Mestrado em Computação';

        $this->actingAs($contexto['user'])
            ->put(route('fichas.update', $ficha->id), $dados)
            ->assertSessionHasNoErrors();

        $ficha->refresh();
        $this->assertNull($ficha->monografia);
        $this->assertNotNull($ficha->dissertacao);

        // Dissertação -> Produto Educacional
        $dados['tipo_ficha_catalografica_id'] = 3;
        $dados['programa'] = 'Mestrado Profissional';
        $dados['produto'] = 'manual';

        $this->actingAs($contexto['user'])
            ->put(route('fichas.update', $ficha->id), $dados)
            ->assertSessionHasNoErrors();

        $ficha->refresh();
        $this->assertNull($ficha->dissertacao);
        $this->assertNotNull($ficha->programaEducacional);
        $this->assertEquals('manual', $ficha->programaEducacional->produto);

        // Produto Educacional -> Tese
        $dados['tipo_ficha_catalografica_id'] = 4;
        $dados['programa'] = 'Doutorado em Biociência';

        $this->actingAs($contexto['user'])
            ->put(route('fichas.update', $ficha->id), $dados)
            ->assertSessionHasNoErrors();

        $ficha->refresh();
        $this->assertNull($ficha->programaEducacional);
        $this->assertNotNull($ficha->tese);
        $this->assertEquals('Doutorado em Biociência', $ficha->tese->programa);
    }

    public function test_atualizacao_substitui_palavras_chave(): void
    {
        $contexto = $this->criarAluno();

        $this->actingAs($contexto['user'])
            ->post(route('fichas.store'), $this->dadosBase())
            ->assertSessionHasNoErrors();

        $ficha = FichaCatalografica::where('aluno_id', $contexto['aluno']->id)->firstOrFail();

        $dados = $this->dadosBase();
        $dados['primeira_chave'] = 'PHP';
        $dados['segunda_chave'] = 'Biblioteca';
        $dados['terceira_chave'] = 'Catalogação';

        $response = $this
            ->actingAs($contexto['user'])
            ->put(route('fichas.update', $ficha->id), $dados);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $ficha->refresh();

        $palavras = $ficha->palavrasChave->pluck('palavra')->all();

        $this->assertCount(3, $palavras);
        $this->assertEqualsCanonicalizing(['PHP', 'Biblioteca', 'Catalogação'], $palavras);
        $this->assertNotContains('Laravel', $palavras);
    }

    public function test_pode_criar_ficha_com_anexo(): void
    {
        $contexto = $this->criarAluno();

        $response = $this
            ->actingAs($contexto['user'])
            ->post(route('fichas.store'), $this->dadosBase());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('fichas.index'));

        $ficha = FichaCatalografica::where('aluno_id', $contexto['aluno']->id)->firstOrFail();

        $this->assertNotEmpty($ficha->anexo);

        Storage::disk(config('filesystems.default'))
            ->assertExists('fichas/' . $ficha->anexo);
    }

    public function test_pode_substituir_anexo_na_atualizacao(): void
    {
        $contexto = $this->criarAluno();

        $this->actingAs($contexto['user'])
            ->post(route('fichas.store'), $this->dadosBase())
            ->assertSessionHasNoErrors();

        $ficha = FichaCatalografica::where('aluno_id', $contexto['aluno']->id)->firstOrFail();
        $disco = Storage::disk(config('filesystems.default'));
        $disco->assertExists('fichas/' . $ficha->anexo);

        $dados = $this->dadosBase();
        $dados['anexo'] = UploadedFile::fake()->createWithContent(
            'novo-trabalho.pdf',
            "%PDF-1.4\nArquivo substituto para teste.\n%%EOF"
        );

        $conteudoNovo = file_get_contents($dados['anexo']->getRealPath());

        $this->actingAs($contexto['user'])
            ->put(route('fichas.update', $ficha->id), $dados)
            ->assertSessionHasNoErrors();

        $ficha->refresh();
        $caminho = 'fichas/' . $ficha->anexo;

        $disco->assertExists($caminho);
        $this->assertSame($conteudoNovo, $disco->get($caminho));
    }

    public function test_cutter_e_obrigatorio(): void
    {
        $contexto = $this->criarAluno();

        $dados = $this->dadosBase();
        $dados['cutter'] = '';

        $response = $this
            ->actingAs($contexto['user'])
            ->post(route('fichas.store'), $dados);

        $response->assertSessionHasErrors(['cutter']);
        $this->assertDatabaseCount('ficha_catalograficas', 0);
    }

    public function test_area_conhecimento_deve_ser_codigo_valido_do_sib(): void
    {
        $contexto = $this->criarAluno();

        $dados = $this->dadosBase();
        $dados['area_conhecimento'] = 'AREA_INVALIDA';

        $response = $this
            ->actingAs($contexto['user'])
            ->post(route('fichas.store'), $dados);

        $response->assertSessionHasErrors(['area_conhecimento']);
        $this->assertDatabaseCount('ficha_catalograficas', 0);
    }

    public function test_titulo_e_obrigatorio(): void
    {
        $contexto = $this->criarAluno();

        $dados = $this->dadosBase();
        $dados['titulo'] = null;

        $response = $this
            ->actingAs($contexto['user'])
            ->post(route('fichas.store'), $dados);

        $response->assertSessionHasErrors(['titulo']);
        $this->assertDatabaseCount('ficha_catalograficas', 0);
    }

    public function test_tipo_ficha_catalografica_e_obrigatorio(): void
    {
        $contexto = $this->criarAluno();

        $dados = $this->dadosBase();
        $dados['tipo_ficha_catalografica_id'] = null;

        $response = $this
            ->actingAs($contexto['user'])
            ->post(route('fichas.store'), $dados);

        $response->assertSessionHasErrors(['tipo_ficha_catalografica_id']);
        $this->assertDatabaseCount('ficha_catalograficas', 0);
    }

    public function test_anexo_e_obrigatorio_na_criacao(): void
    {
        $contexto = $this->criarAluno();

        $dados = $this->dadosBase();
        unset($dados['anexo']);

        $response = $this
            ->actingAs($contexto['user'])
            ->post(route('fichas.store'), $dados);

        $response->assertSessionHasErrors(['anexo']);
        $this->assertDatabaseCount('ficha_catalograficas', 0);
    }

    public function test_usuario_nao_autenticado_nao_pode_acessar_rotas_de_ficha(): void
    {
        $contexto = $this->criarAluno();

        $this->actingAs($contexto['user'])
            ->post(route('fichas.store'), $this->dadosBase())
            ->assertSessionHasNoErrors();

        $ficha = FichaCatalografica::where('aluno_id', $contexto['aluno']->id)->firstOrFail();

        auth()->logout();

        $this->post(route('fichas.store'), $this->dadosBase())->assertRedirect();
        $this->get(route('fichas.edit', $ficha))->assertRedirect(route('login'));
        $this->put(route('fichas.update', $ficha->id), $this->dadosBase())->assertRedirect(route('login'));
        $this->get(route('fichas.gerar', $ficha->id))->assertRedirect(route('login'));
    }

    public function test_aluno_nao_pode_editar_atualizar_ou_baixar_ficha_de_outro_aluno(): void
    {
        $primeiro = $this->criarAluno();
        $segundo = $this->criarAluno();

        $this->actingAs($segundo['user'])
            ->post(route('fichas.store'), $this->dadosBase())
            ->assertSessionHasNoErrors();

        $ficha = FichaCatalografica::where('aluno_id', $segundo['aluno']->id)->firstOrFail();
        $tituloOriginal = $ficha->titulo;

        // Não pode abrir edição
        $this->actingAs($primeiro['user'])
            ->get(route('fichas.edit', $ficha))
            ->assertRedirect(route('fichas.index'))
            ->assertSessionHas('fail', 'Você não tem permissão para editar esta ficha.');

        // Não pode atualizar
        $dados = $this->dadosBase();
        $dados['titulo'] = 'Tentativa de alteração indevida';

        $this->actingAs($primeiro['user'])
            ->put(route('fichas.update', $ficha->id), $dados)
            ->assertRedirect(url()->previous())
            ->assertSessionHas('fail', 'Você não tem permissão para editar esta ficha.');

        // Não pode baixar PDF
        $this->actingAs($primeiro['user'])
            ->get(route('fichas.gerar', $ficha->id))
            ->assertForbidden();

        $ficha->refresh();
        $this->assertEquals($segundo['aluno']->id, $ficha->aluno_id);
        $this->assertSame($tituloOriginal, $ficha->titulo);
    }

    public function test_bibliotecario_pode_editar_atualizar_e_baixar_ficha(): void
    {
        $contexto = $this->criarAluno();
        $bibliotecario = $this->criarBibliotecario();

        $this->actingAs($contexto['user'])
            ->post(route('fichas.store'), $this->dadosBase())
            ->assertSessionHasNoErrors();

        $ficha = FichaCatalografica::where('aluno_id', $contexto['aluno']->id)->firstOrFail();

        $this->actingAs($bibliotecario)
            ->get(route('fichas.edit', $ficha))
            ->assertOk();

        $dados = $this->dadosBase();
        unset($dados['anexo']);
        $dados['cutter'] = 'S587';
        $dados['area_conhecimento'] = AreaConhecimento::EDUCACAO->value;

        $this->actingAs($bibliotecario)
            ->put(route('fichas.update', $ficha->id), $dados)
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('listar-fichas'));

        $ficha->refresh();
        $this->assertEquals('S587', $ficha->cutter);
        $this->assertEquals('370', $ficha->area_conhecimento);

        $this->actingAs($bibliotecario)
            ->get(route('fichas.gerar', $ficha->id))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}
