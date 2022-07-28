<?php

namespace App\Domains\Admin\Admin\Pages;

use App\Components\LoginHistoryable\Services\LoginDetailsService;
use Filament\Http\Livewire\Auth\Login as BaseLogin;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

final class Login extends BaseLogin
{
    public function mount(): void
    {
        parent::mount();

        $this->fillForm();
    }

    public function authenticate(): ?LoginResponse
    {
        $response = parent::authenticate();

        $this->updateLoginHistory();

        return $response;
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

    private function updateLoginHistory(): void
    {
        /** @var Authenticatable $user */
        $user = Auth::guard('admin')->user();
        $loginDetailsService = app(LoginDetailsService::class);
        $loginDetails = $loginDetailsService->getLoginDetails(request());
        $loginDetailsService->updateLoginHistory($user, $loginDetails);
    }
}
