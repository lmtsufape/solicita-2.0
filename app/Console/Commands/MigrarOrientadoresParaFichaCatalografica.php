<?php

namespace App\Console\Commands;

use App\Models\FichaCatalografica;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrarOrientadoresParaFichaCatalografica extends Command
{
    protected $signature = 'fichas:migrar-orientadores';

    protected $description = 'Copia os dados de orientação das monografias, dissertações, teses e produtos educacionais para as fichas catalográficas';

    public function handle()
    {
        $campos = [
            'nome_orientador',
            'sobrenome_orientador',
            'nome_coorientador',
            'sobrenome_coorientador',
            'nome_segundo_coorientador',
            'sobrenome_segundo_coorientador',
        ];

        $atualizadas = 0;
        $semOrigem = 0;
        $semAlteracoes = 0;

        DB::transaction(function () use (
            $campos,
            &$atualizadas,
            &$semOrigem,
            &$semAlteracoes
        ) {
            FichaCatalografica::query()
                ->whereIn('tipo_ficha_catalografica_id', [1, 2, 3, 4])
                ->with(['monografia', 'dissertacao', 'programaEducacional', 'tese', 'tipoFichaCatalografica'])
                ->chunkById(200, function ($fichas) use (
                    $campos,
                    &$atualizadas,
                    &$semOrigem,
                    &$semAlteracoes
                ) {
                    foreach ($fichas as $ficha) {
                        $origem = match ($ficha->tipoFichaCatalografica?->slug) {
                            'dissertacao' => $ficha->dissertacao,
                            'monografia' => $ficha->monografia,
                            'produto-educacional', 'programa_educacional' => $ficha->programaEducacional,
                            'tese' => $ficha->tese,
                            default => match ((int) $ficha->tipo_ficha_catalografica_id) {
                                1 => $ficha->dissertacao,
                                2 => $ficha->monografia,
                                3 => $ficha->programaEducacional,
                                4 => $ficha->tese,
                                default => null,
                            },
                        };

                        if (!$origem) {
                            $semOrigem++;

                            $this->warn(
                                "Ficha {$ficha->id}: registro de origem não encontrado."
                            );

                            continue;
                        }

                        $dados = [];

                        foreach ($campos as $campo) {
                            $valorAtual = $ficha->getRawOriginal($campo);
                            $valorOrigem = $origem->getRawOriginal($campo);

                            if (
                                ($valorAtual === null || $valorAtual === '') &&
                                $valorOrigem !== null &&
                                $valorOrigem !== ''
                            ) {
                                $dados[$campo] = $valorOrigem;
                            }
                        }

                        if (empty($dados)) {
                            $semAlteracoes++;
                            continue;
                        }

                        DB::table($ficha->getTable())
                            ->where($ficha->getKeyName(), $ficha->getKey())
                            ->update($dados);

                        $atualizadas++;
                    }
                });
        });

        $this->info("Fichas atualizadas: {$atualizadas}");
        $this->line("Fichas sem alterações: {$semAlteracoes}");
        $this->line("Fichas sem registro de origem: {$semOrigem}");

        return 0;
    }
}
