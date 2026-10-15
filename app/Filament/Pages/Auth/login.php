<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Http\Responses\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        try {
            $data = $this->form->getState();

            // 1. Cek email & password bawaan Laravel/Filament
            if (! auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
                $this->throwFailureValidationException();
            }

            $user = auth()->user();

            // 2. Cegat jika user adalah Peserta DAN (belum punya data / is_active masih false)
            if ($user->hasRole('peserta') && (! $user->peserta || ! $user->peserta->is_active)) {
                auth()->logout();
                session()->invalidate();
                session()->regenerateToken();

                throw ValidationException::withMessages([
                    'data.email' => 'Akun Anda belum diverifikasi oleh Admin. Silakan tunggu proses verifikasi berkas.',
                ]);
            }

            return app(LoginResponse::class);

        } catch (ValidationException $exception) {
            throw $exception;
        }
    }
}