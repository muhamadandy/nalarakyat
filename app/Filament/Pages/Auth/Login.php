<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    public function getHeading(): string | Htmlable
    {
        return 'Masuk ke Akun Nalarakyat';
    }

    public function getSubHeading(): string | Htmlable | null
    {
        return 'Selamat datang kembali. Silakan masuk untuk melanjutkan.';
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email')
            ->placeholder('Masukkan alamat email')
            ->prefixIcon('heroicon-o-envelope')
            ->email()
            ->required()
            ->autofocus()
            ->autocomplete()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Kata Sandi')
            ->placeholder('Masukkan kata sandi')
            ->prefixIcon('heroicon-o-key')
            ->password()
            ->revealable()
            ->required()
            ->extraInputAttributes(['tabindex' => 2]);
    }

    protected function getRememberFormComponent(): Component
    {
        return Checkbox::make('remember')
            ->label('Ingat saya');
    }
}
