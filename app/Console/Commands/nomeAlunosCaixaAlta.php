<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class nomeAlunosCaixaAlta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alunos:toupper';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::where('tipo', 'aluno')->get();

        foreach($users as $user) {
            $user->update(['name' => mb_strtoupper($user->name)]);
        }
    }
}
