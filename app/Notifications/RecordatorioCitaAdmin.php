<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RecordatorioCitaAdmin extends Notification
{
    use Queueable;

    public $cita;

    public function __construct($cita)
    {
        $this->cita = $cita;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Recordatorio de Citas Agendadas 🗓️')
            ->greeting('Hola ' . ($notifiable->cliente->nombre ?? 'Administrador') . '!')
            ->line('Te recordamos que hay citas agendadas próximamente.')
            ->line('👤 Cliente: ' . $this->cita->cliente->nombre)
            ->line('📅 Fecha: ' . $this->cita->fecha)
            ->line('⏰ Hora: ' . $this->cita->hora)
            ->line('💅 Servicio: ' . $this->cita->servicio->Nom_Servicio)
            ->line('¡Recuerda comunicarle a tu empleado! ⚠️');
    }
}
