<?php

namespace App\Console\Commands\SalesHunter;

use App\Cliente;
use Illuminate\Console\Command;
use App\Jobs\JobStoreProdutoCadastrado;

class ProdutosCadastradosPorCliente extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'saleshunter:produtos-cliente';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registra os produtoscomprados nos últimos 8 meses de cada cliente';

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
        $clientes = Cliente::all();

        foreach ($clientes as &$cliente) {
            dispatch(new JobStoreProdutoCadastrado($cliente));

            /**
             * Usar a linha comentada a baixo para rodar o job sem precisar de queue
             */
            // new JobStoreProdutoCadastrado($cliente);
            unset($cliente);
        }
        unset($clientes);
    }
}
