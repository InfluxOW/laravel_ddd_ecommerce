<?php

namespace App\Domains\Admin\Admin\Pages;

use Filament\Http\Livewire\Auth\Login as BaseLogin;

final class Login extends BaseLogin
{
    public function mount(): void
    {
        parent::mount();

        $this->fillForm();
    }

    private function fillForm(): void
    {
        if (app()->isProduction()) {
            return;
        }

        $this->form->fill([
            'email' => config('app.admin.email'),
            'password' => config('app.admin.password'),
            'remember' => true,
        ]);
    }
}
