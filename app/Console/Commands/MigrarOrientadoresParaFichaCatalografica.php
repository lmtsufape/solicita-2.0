<?php

namespace App\Console\Commands;

use App\Models\FichaCatalografica;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrarOrientadoresParaFichaCatalografica extends Command
{
    protected $signature = 'fichas:migrar-orientadores';

    protected $description = 'Copia os dados de orientação das monografias e dissertações para as fichas catalográficas';

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
                ->whereIn('tipo_ficha_catalografica_id', [1, 2])
                ->with(['monografia', 'dissertacao'])
                ->chunkById(200, function ($fichas) use (
                    $campos,
                    &$atualizadas,
                    &$semOrigem,
                    &$semAlteracoes
                ) {
                    foreach ($fichas as $ficha) {
                        $origem = (int) $ficha->tipo_ficha_catalografica_id === 1
                            ? $ficha->dissertacao
                            : $ficha->monografia;

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
