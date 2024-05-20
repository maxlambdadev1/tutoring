<?php

use App\Livewire\Common\Forms\LoginForm;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('admin.layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: RouteServiceProvider::HOME, navigate: false);
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />

    <form wire:submit="login">
        <!-- Email Address -->
        <div>
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                <input wire:model="form.email" class="form-control @error('form.email') is-invalid @enderror" type="email"  id="email"  name="email" required placeholder="Enter your email" autofocus autocomplete="username">                
            </div>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <div class="input-group input-group-merge">
                <input wire:model="form.password" type="password" id="password" class="form-control @error('form.password') is-invalid @enderror" name="password" placeholder="Enter your password" required autocomplete="current-password">
                <div class="input-group-text" data-password="false">
                    <span class="password-eye"></span>
                </div>
            </div>
        </div>

        <!-- Remember Me -->
        <div class="mt-4">
            <div class="form-check">
                <input wire:model="form.remember" type="checkbox" class="form-check-input" name="remember" id="remember">
                <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
            </div>
        </div>

        {{-- @if (Route::has('password.request'))
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}" wire:navigate>
                {{ __('Forgot your password?') }}
            </a>
        @endif --}}

        <div class="mb-3 mb-0 text-center">
            <button class="btn btn-primary" type="submit"> {{ __('Login') }} </button>
        </div>
    </form>
</div>
