<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\FichaCatalografica;
use App\Models\Perfil;
use App\Models\TipoDocumento;
use App\Models\Unidade;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Middleware\ThrottleRequests;
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

        TipoDocumento::query()->forceCreate([
            'id' => 1,
            'tipo' => 'Dissertação',
        ]);

        TipoDocumento::query()->forceCreate([
            'id' => 2,
            'tipo' => 'Monografia',
        ]);
    }

    private function criarAluno(): array
    {
        $user = User::factory()->create([
            'tipo' => 'aluno',
        ]);

        $aluno = Aluno::factory()->create([
            'user_id' => $user->id,
        ]);

        $unidade = Unidade::factory()->create([
            'nome' => 'Campus Garanhuns',
        ]);

        $perfil = Perfil::factory()->create([
            'aluno_id' => $aluno->id,
            'unidade_id' => $unidade->id,
            'default' => 'Ciência da Computação',
        ]);

        return [
            'user' => $user,
            'aluno' => $aluno,
            'perfil' => $perfil,
            'unidade' => $unidade,
        ];
    }

    private function dadosBase(): array
    {
        return [
            'tipo_documento' => 2,
            'autor_nome' => 'João',
            'autor_sobrenome' => 'Silva',
            'cutter' => 'S586',
            'area_conhecimento' => 'Ciência da Computação',
            'titulo' => 'Sistema de Gestão Acadêmica',
            'subtitulo' => 'Uma abordagem utilizando Laravel',
            'local' => 'Garanhuns',
            'ano' => 2026,
            'folhas' => 80,
            'ilustracao' => 'Sim',
            'inclui_anexo' => 0,
            'inclui_apendice' => 0,
            'nome_orientador' => 'Maria',
            'sobrenome_orientador' => 'Souza',
            'nome_coorientador' => null,
            'sobrenome_coorientador' => null,
            'nome_segundo_coorientador' => null,
            'sobrenome_segundo_coorientador' => null,
            'tipo_curso' => 'Graduação',
            'programa' => 'Programa de Pós-Graduação',
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

    public function test_aluno_pode_criar_monografia(): void
    {
        $contexto = $this->criarAluno();

        $response = $this
            ->actingAs($contexto['user'])
            ->post(route('fichas.store'), $this->dadosBase());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('home-aluno'));

        $this->assertDatabaseHas('ficha_catalograficas', [
            'aluno_id' => $contexto['aluno']->id,
            'tipo_documento_id' => 2,
            'autor_nome' => 'João',
            'autor_sobrenome' => 'Silva',
            'titulo' => 'Sistema de Gestão Acadêmica',
            'nome_orientador' => 'Maria',
            'sobrenome_orientador' => 'Souza',
        ]);

        $ficha = FichaCatalografica::where(
            'aluno_id',
            $contexto['aluno']->id
        )->firstOrFail();

        $this->assertNotNull($ficha->monografia);
        $this->assertNull($ficha->dissertacao);

        $this->assertEquals(
            'Ciência da Computação',
            $ficha->monografia->curso
        );

        $this->assertEquals(
            'Graduação',
            $ficha->monografia->tipo_curso
        );

        $this->assertEquals(
            'Campus Garanhuns',
            $ficha->monografia->campus
        );

    }

    public function test_aluno_pode_criar_dissertacao(): void
    {
        $contexto = $this->criarAluno();

        $dados = $this->dadosBase();
        $dados['tipo_documento'] = 1;
        $dados['programa'] = 'Mestrado em Ciência da Computação';

        $response = $this
            ->actingAs($contexto['user'])
            ->post(route('fichas.store'), $dados);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('home-aluno'));

        $ficha = FichaCatalografica::where(
            'aluno_id',
            $contexto['aluno']->id
        )->firstOrFail();

        $this->assertEquals(1, (int) $ficha->tipo_documento_id);
        $this->assertNotNull($ficha->dissertacao);
        $this->assertNull($ficha->monografia);

        $this->assertEquals(
            'Mestrado em Ciência da Computação',
            $ficha->dissertacao->programa
        );

        $this->assertEquals(
            'Campus Garanhuns',
            $ficha->dissertacao->campus
        );
    }

    public function test_cria_palavras_chave_da_ficha(): void
    {
        $contexto = $this->criarAluno();

        $response = $this
            ->actingAs($contexto['user'])
            ->post(route('fichas.store'), $this->dadosBase());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('home-aluno'));

        $ficha = FichaCatalografica::where(
            'aluno_id',
            $contexto['aluno']->id
        )->firstOrFail();

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
        $response->assertRedirect(route('home-aluno'));

        $ficha = FichaCatalografica::where(
            'aluno_id',
            $contexto['aluno']->id
        )->firstOrFail();

        $palavras = $ficha->palavrasChave->pluck('palavra')->all();

        $this->assertCount(3, $palavras);

        foreach ($palavras as $palavra) {
            $this->assertNotNull($palavra);
            $this->assertNotSame('', trim($palavra));
        }
    }

    public function test_aluno_pode_atualizar_monografia(): void
    {
        $contexto = $this->criarAluno();

        $response = $this
            ->actingAs($contexto['user'])
            ->post(route('fichas.store'), $this->dadosBase());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('home-aluno'));

        $ficha = FichaCatalografica::where(
            'aluno_id',
            $contexto['aluno']->id
        )->firstOrFail();

        $dados = $this->dadosBase();
        $dados['titulo'] = 'Título atualizado';
        $dados['subtitulo'] = 'Novo subtítulo';
        $dados['tipo_curso'] = 'Bacharelado';

        $response = $this
            ->actingAs($contexto['user'])
            ->put(route('fichas.update', $ficha->id), $dados);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $ficha->refresh();

        $this->assertEquals('Título atualizado', $ficha->titulo);
        $this->assertEquals('Novo subtítulo', $ficha->subtitulo);
        $this->assertNotNull($ficha->monografia);

        $this->assertEquals(
            'Bacharelado',
            $ficha->monografia->tipo_curso
        );
    }

    public function test_pode_mudar_monografia_para_dissertacao(): void
    {
        $contexto = $this->criarAluno();

        $response = $this
            ->actingAs($contexto['user'])
            ->post(route('fichas.store'), $this->dadosBase());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('home-aluno'));

        $ficha = FichaCatalografica::where(
            'aluno_id',
            $contexto['aluno']->id
        )->firstOrFail();

        $this->assertNotNull($ficha->monografia);

        $dados = $this->dadosBase();
        $dados['tipo_documento'] = 1;
        $dados['titulo'] = 'Nova Dissertação';
        $dados['programa'] = 'Mestrado em Computação';

        $response = $this
            ->actingAs($contexto['user'])
            ->put(route('fichas.update', $ficha->id), $dados);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $ficha->refresh();

        $this->assertEquals(1, (int) $ficha->tipo_documento_id);
        $this->assertEquals('Nova Dissertação', $ficha->titulo);
        $this->assertNull($ficha->monografia);
        $this->assertNotNull($ficha->dissertacao);

        $this->assertEquals(
            'Mestrado em Computação',
            $ficha->dissertacao->programa
        );
    }

    public function test_atualizacao_substitui_palavras_chave(): void
    {
        $contexto = $this->criarAluno();

        $response = $this
            ->actingAs($contexto['user'])
            ->post(route('fichas.store'), $this->dadosBase());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('home-aluno'));

        $ficha = FichaCatalografica::where(
            'aluno_id',
            $contexto['aluno']->id
        )->firstOrFail();

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

        $this->assertEqualsCanonicalizing(
            ['PHP', 'Biblioteca', 'Catalogação'],
            $palavras
        );

        $this->assertNotContains('Laravel', $palavras);
        $this->assertNotContains('Sistema', $palavras);
        $this->assertNotContains('Gestão', $palavras);
    }

    public function test_pode_criar_ficha_com_anexo(): void
    {
        $contexto = $this->criarAluno();

        $response = $this
            ->actingAs($contexto['user'])
            ->post(route('fichas.store'), $this->dadosBase());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('home-aluno'));

        $ficha = FichaCatalografica::where(
            'aluno_id',
            $contexto['aluno']->id
        )->firstOrFail();

        $this->assertNotEmpty($ficha->anexo);

        Storage::disk(config('filesystems.default'))
            ->assertExists('fichas/' . $ficha->anexo);
    }

    public function test_pode_substituir_anexo_na_atualizacao(): void
    {
        $contexto = $this->criarAluno();

        $response = $this
            ->actingAs($contexto['user'])
            ->post(route('fichas.store'), $this->dadosBase());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('home-aluno'));

        $ficha = FichaCatalografica::where(
            'aluno_id',
            $contexto['aluno']->id
        )->firstOrFail();

        $this->assertNotEmpty($ficha->anexo);

        $disco = Storage::disk(config('filesystems.default'));

        $disco->assertExists('fichas/' . $ficha->anexo);

        $dados = $this->dadosBase();

        $dados['anexo'] = UploadedFile::fake()->createWithContent(
            'novo-trabalho.pdf',
            "%PDF-1.4\nArquivo substituto para teste.\n%%EOF"
        );

        $conteudoNovo = file_get_contents(
            $dados['anexo']->getRealPath()
        );

        $response = $this
            ->actingAs($contexto['user'])
            ->put(route('fichas.update', $ficha->id), $dados);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $ficha->refresh();

        $this->assertNotEmpty($ficha->anexo);

        $caminho = 'fichas/' . $ficha->anexo;

        $disco->assertExists($caminho);

        $this->assertSame(
            $conteudoNovo,
            $disco->get($caminho)
        );
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

    public function test_tipo_documento_e_obrigatorio(): void
    {
        $contexto = $this->criarAluno();

        $dados = $this->dadosBase();
        $dados['tipo_documento'] = null;

        $response = $this
            ->actingAs($contexto['user'])
            ->post(route('fichas.store'), $dados);

        $response->assertSessionHasErrors(['tipo_documento']);

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

    public function test_usuario_nao_autenticado_nao_pode_criar_ficha(): void
    {
        $response = $this->post(
            route('fichas.store'),
            $this->dadosBase()
        );

        $response->assertRedirect();

        $this->assertDatabaseCount('ficha_catalograficas', 0);
    }

    public function test_aluno_nao_pode_atualizar_ficha_de_outro_aluno(): void
    {
        $primeiro = $this->criarAluno();
        $segundo = $this->criarAluno();

        $response = $this
            ->actingAs($segundo['user'])
            ->post(route('fichas.store'), $this->dadosBase());

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('home-aluno'));

        $ficha = FichaCatalografica::where(
            'aluno_id',
            $segundo['aluno']->id
        )->firstOrFail();

        $tituloOriginal = $ficha->titulo;

        $dados = $this->dadosBase();
        $dados['titulo'] = 'Tentativa de alteração indevida';

        $response = $this
            ->actingAs($primeiro['user'])
            ->put(route('fichas.update', $ficha->id), $dados);

        $response->assertNotFound();

        $ficha->refresh();

        $this->assertEquals(
            $segundo['aluno']->id,
            $ficha->aluno_id
        );

        $this->assertSame($tituloOriginal, $ficha->titulo);
    }
}
