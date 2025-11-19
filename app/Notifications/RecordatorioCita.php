<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RecordatorioCita extends Notification
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
            ->subject('Recordatorio de tu cita 🗓️')
            ->greeting('Hola ' . $this->cita->cliente->nombre . '!')
            ->line('Te recordamos que tienes una cita próximamente.')
            ->line('📅 Fecha: ' . $this->cita->fecha)
            ->line('⏰ Hora: ' . $this->cita->hora)
            ->line('💅 Servicio: ' . $this->cita->servicio->Nom_Servicio)
            ->line('¡Te esperamos! 😊');
    }
}
