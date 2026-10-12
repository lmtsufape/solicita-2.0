<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PreencherSlugsTiposFichasCatalograficas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fichas:preencher-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Preenche os slugs dos tipos de fichas catalográficas';

    public function handle()
    {
        $tipos = [
            1 => 'Dissertação',
            2 => 'Monografia',
            3 => 'Produto Educacional',
            4 => 'Tese',
        ];

        DB::transaction(function () use ($tipos) {
            foreach ($tipos as $id => $nome) {
                DB::table('tipos_fichas_catalograficas')
                    ->where('id', $id)
                    ->update([
                        'nome' => $nome,
                        'slug' => Str::slug($nome),
                    ]);
            }
        });

        $this->info('Nomes e slugs atualizados com sucesso.');

        return self::SUCCESS;
    }
}
