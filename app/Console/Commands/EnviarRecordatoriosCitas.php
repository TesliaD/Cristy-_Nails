<?php

namespace App\Console\Commands;

use App\Models\Cita;
use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class EnviarRecordatoriosCitas extends Command
{
    protected $signature = 'citas:recordatorios';
    protected $description = 'Envía recordatorios de citas a clientes y administradores';

    public function handle()
    {
        $manana = Carbon::tomorrow('America/Phoenix')->format('Y-m-d');

        // Obtener citas del día siguiente
        $citas = Cita::with(['cliente.usuario', 'servicio'])
            ->whereDate('fecha', $manana)
            ->where('estado', 'pendiente')
            ->get();

        // Obtener administradores
        $admins = User::where('rol', 'admin')->with('cliente')->get();

        foreach ($citas as $cita) {

            // 👉 1. Enviar correo al cliente
            if ($cita->cliente && $cita->cliente->usuario) {
                $cita->cliente->usuario->notify(
                    new \App\Notifications\RecordatorioCita($cita)
                );
            }

            // 👉 2. Enviar correo a los administradores
            foreach ($admins as $admin) {
                $admin->notify(
                    new \App\Notifications\RecordatorioCitaAdmin($cita)
                );
            }
        }

        $this->info('Recordatorios enviados correctamente a clientes y administradores.');
    }
}
