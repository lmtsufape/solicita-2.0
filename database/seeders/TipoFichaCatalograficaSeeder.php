<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TipoFichaCatalograficaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documentos = ['Dissertação', 'Monografia', 'Produto Educacional', 'Tese'];

        for ($i = 0; $i < sizeof($documentos); $i++) {
            DB::table('tipos_fichas_catalograficas')->insert([
                'nome' => $documentos[$i],
                'slug' => Str::slug($documentos[$i]),
            ]);
        }
    }
}
