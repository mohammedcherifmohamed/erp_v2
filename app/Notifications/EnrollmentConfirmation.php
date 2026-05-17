<?php

namespace App\Notifications;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnrollmentConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Enrollment $enrollment) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $classe = $this->enrollment->classe;
        $course = $this->enrollment->course;

        $mail = (new MailMessage)
            ->subject('Confirmation d\'inscription - ' . config('app.name'))
            ->greeting('Bonjour ' . $this->enrollment->student->first_name . ' !')
            ->line('Votre demande d\'inscription a bien été enregistrée.')
            ->line('Section : ' . $classe->name);

        if ($course) {
            $mail->line('Cours : ' . $course->name);
        }

        $mail
            ->line('Date de début : ' . ($this->enrollment->start_date ? $this->enrollment->start_date->format('d/m/Y') : 'En attente de validation'))
            ->line('Date d\'expiration de la demande : ' . ($this->enrollment->expires_at ? $this->enrollment->expires_at->format('d/m/Y') : ''))
            ->line('Votre inscription est actuellement en attente de validation par l\'administration. Vous recevrez une confirmation par email dès qu\'elle sera traitée.')
            ->action('Voir mes inscriptions', route('student.dashboard'))
            ->salutation('L\'équipe ' . config('app.name'));

        return $mail;
    }
}
