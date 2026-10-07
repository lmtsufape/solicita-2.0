<?php

namespace App\Console\Commands;

use App\Models\FichaCatalografica;
use Illuminate\Console\Command;

class PreencherAlunoFichaCatalografica extends Command
{
    protected $signature = 'ficha-catalografica:preencher-alunos';

    protected $description = 'Preenche o aluno_id das fichas catalográficas com base nas requisições';

    public function handle(): int
    {
        $this->info('Iniciando preenchimento dos alunos...');

        $atualizadas = 0;
        $ignoradas = 0;

        FichaCatalografica::query()
            ->whereNull('aluno_id')
            ->with('requisicaoDocumento')
            ->chunkById(100, function ($fichas) use (&$atualizadas, &$ignoradas) {
                foreach ($fichas as $ficha) {
                    $alunoId = $ficha->requisicaoDocumento?->aluno_id;

                    if (!$alunoId) {
                        $ignoradas++;

                        $this->warn(
                            "Ficha {$ficha->id}: aluno não encontrado na requisição."
                        );

                        continue;
                    }

                    $ficha->update([
                        'aluno_id' => $alunoId,
                    ]);

                    $atualizadas++;
                }
            });

        $this->newLine();

        $this->info("Fichas atualizadas: {$atualizadas}");
        $this->info("Fichas ignoradas: {$ignoradas}");

        return self::SUCCESS;
    }
}
