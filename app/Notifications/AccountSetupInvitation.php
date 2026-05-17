<?php

namespace App\Notifications;

use App\Models\PasswordSetupToken;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountSetupInvitation extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $user,
        public PasswordSetupToken $token,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $roleLabels = [
            'student' => 'étudiant',
            'teacher' => 'enseignant',
            'parent' => 'parent',
        ];

        $roleLabel = $roleLabels[$this->user->role] ?? 'utilisateur';

        return (new MailMessage)
            ->subject('Configuration de votre compte ' . ucfirst($roleLabel) . ' - ' . config('app.name'))
            ->greeting('Bonjour ' . $this->user->first_name . ' !')
            ->line('Un compte ' . $roleLabel . ' a été créé pour vous sur ' . config('app.name') . '.')
            ->line('Pour activer votre compte et définir votre mot de passe, veuillez cliquer sur le bouton ci-dessous :')
            ->action('Configurer mon mot de passe', $this->token->getSetupUrl())
            ->line('Ce lien de configuration est valable jusqu\'au ' . $this->token->expires_at->format('d/m/Y à H:i') . '.')
            ->line('Si vous n\'avez pas demandé la création de ce compte, vous pouvez ignorer cet email.')
            ->salutation('L\'équipe ' . config('app.name'));
    }
}
