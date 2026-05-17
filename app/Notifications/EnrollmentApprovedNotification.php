<?php

namespace App\Notifications;

use App\Models\Enrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnrollmentApprovedNotification extends Notification implements ShouldQueue
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
        $isParent = $notifiable->isParent();
        $recipientName = $isParent
            ? $this->enrollment->student->first_name . ' (parent)'
            : $this->enrollment->student->first_name;

        $mail = (new MailMessage)
            ->subject('Inscription approuvée - ' . config('app.name'))
            ->greeting('Bonjour ' . $recipientName . ' !')
            ->line('Votre inscription a été approuvée avec succès !')
            ->line('Section : ' . $classe->name);

        if ($course) {
            $mail->line('Cours : ' . $course->name);
        } else {
            $courseNames = $classe->courses->pluck('name')->implode(', ');
            if ($courseNames) {
                $mail->line('Cours inclus : ' . $courseNames);
            }
        }

        if ($this->enrollment->start_date) {
            $mail->line('Date de début : ' . $this->enrollment->start_date->format('d/m/Y'));
        }
        if ($this->enrollment->end_date) {
            $mail->line('Date de fin : ' . $this->enrollment->end_date->format('d/m/Y'));
        }

        $mail
            ->action('Accéder au tableau de bord', route('student.dashboard'))
            ->line('Vous pouvez maintenant consulter votre emploi du temps et accéder à vos cours.')
            ->salutation('L\'équipe ' . config('app.name'));

        return $mail;
    }
}
