<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeacherApplicationReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $teacher) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirmation de votre candidature - ' . config('app.name'))
            ->greeting('Bonjour ' . $this->teacher->first_name . ' !')
            ->line('Nous avons bien reçu votre candidature pour rejoindre notre équipe pédagogique.')
            ->line('Votre dossier est actuellement en cours d\'examen par notre équipe administrative.')
            ->line('Vous recevrez une notification dès que votre candidature sera approuvée.')
            ->line('En attendant, n\'hésitez pas à consulter notre plateforme pour plus d\'informations.')
            ->action('Visiter notre plateforme', route('home'))
            ->salutation('L\'équipe ' . config('app.name'));
    }
}
