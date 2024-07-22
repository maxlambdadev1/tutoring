<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('recruiter.layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <!-- title-->
    <h4 class="mt-0">Reset Password</h4>
    <p class="text-muted mb-4">{{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}</p>
    <!-- Session Status -->
    
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <x-input-error :messages="$errors->get('email')" class="mt-2" />

    <form wire:submit="sendPasswordResetLink">
        <!-- Email Address -->
        <div>
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                <input wire:model="email" class="form-control @error('email') is-invalid @enderror" type="email"  id="email"  name="email" required placeholder="Enter your email" autofocus autocomplete="username">                
            </div>
        </div>

        <div class="d-grid mb-3 mb-0 text-center mt-3">
            <button class="btn btn-primary" type="submit"><i class="mdi mdi-login"></i> {{ __('Email Password Reset Link') }} </button>
        </div>

        <div class="mt-4 text-center">
            <p>Remember It ? <a href="{{route('recruiter.login')}}" class="fw-medium text-primary" wire:navigate> Sign In here</a>
        </div>
    </form>
</div>
