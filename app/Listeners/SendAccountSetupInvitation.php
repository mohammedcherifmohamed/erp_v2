<?php

namespace App\Listeners;

use App\Events\AccountCreatedByAdmin;
use App\Models\PasswordSetupToken;
use App\Notifications\AccountSetupInvitation;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAccountSetupInvitation implements ShouldQueue
{
    public function handle(AccountCreatedByAdmin $event): void
    {
        $token = PasswordSetupToken::createForUser($event->user);
        $event->user->notify(new AccountSetupInvitation($event->user, $token));
    }
}
