<?php

namespace App\Notifications;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnrollmentRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Enrollment $enrollment) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $reason = $this->enrollment->rejection_reason;

        $mail = (new MailMessage)
            ->subject('Inscription refusée - ' . config('app.name'))
            ->greeting('Bonjour ' . $this->enrollment->student->first_name . ' !')
            ->line('Nous sommes désolés de vous informer que votre demande d\'inscription a été refusée.');

        if ($reason) {
            $mail->line('Motif : ' . $reason);
        }

        $mail
            ->line('Si vous avez des questions, n\'hésitez pas à contacter notre équipe administrative pour plus d\'informations.')
            ->action('Visiter notre plateforme', route('home'))
            ->salutation('L\'équipe ' . config('app.name'));

        return $mail;
    }
}
