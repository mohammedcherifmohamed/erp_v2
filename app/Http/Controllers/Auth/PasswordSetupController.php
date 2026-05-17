<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordSetupToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordSetupController extends Controller
{
    public function showSetupForm(string $token)
    {
        $tokenModel = PasswordSetupToken::where('token', $token)->firstOrFail();

        if (!$tokenModel->isValid()) {
            return redirect()->route('login')
                ->with('error', 'Ce lien de configuration a expiré ou a déjà été utilisé.');
        }

        return view('auth.password-setup', compact('tokenModel'));
    }

    public function setupPassword(Request $request, string $token)
    {
        $tokenModel = PasswordSetupToken::where('token', $token)->firstOrFail();

        if (!$tokenModel->isValid()) {
            return redirect()->route('login')
                ->with('error', 'Ce lien de configuration a expiré ou a déjà été utilisé.');
        }

        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $tokenModel->user->update([
            'password' => Hash::make($data['password']),
        ]);

        $tokenModel->update(['used_at' => now()]);

        return redirect()->route('login')
            ->with('success', 'Votre mot de passe a été configuré avec succès. Vous pouvez maintenant vous connecter.');
    }
}
