<?php

namespace App\Console\Commands;
use App\Models\Cita;
use Illuminate\Console\Command;
use Carbon\Carbon;

class EnviarRecordatoriosCitas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'citas:recordatorios';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $manana = Carbon::tomorrow('America/Phoenix')->format('Y-m-d');


        $citas = Cita::with(['cliente.usuario', 'servicio'])
            ->whereDate('fecha', $manana)
            ->where('estado', 'pendiente')
            ->get();

        foreach ($citas as $cita) {
            if ($cita->cliente && $cita->cliente->usuario) {
                $cita->cliente->usuario->notify(
                    new \App\Notifications\RecordatorioCita($cita)
                );
            }
        }

        $this->info('Recordatorios enviados correctamente.');
    }
}