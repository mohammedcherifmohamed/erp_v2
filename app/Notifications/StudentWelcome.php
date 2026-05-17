<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentWelcome extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $student) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Bienvenue sur ' . config('app.name'))
            ->greeting('Bonjour ' . $this->student->first_name . ' !')
            ->line('Votre compte étudiant a été créé avec succès sur notre plateforme éducative.')
            ->line('Vous pouvez désormais explorer les cours disponibles, vous inscrire aux sections qui vous intéressent et suivre votre progression pédagogique.')
            ->action('Accéder au tableau de bord', route('student.dashboard'))
            ->line('Nous vous souhaitons une excellente expérience d\'apprentissage !')
            ->salutation('L\'équipe ' . config('app.name'));
    }
}
