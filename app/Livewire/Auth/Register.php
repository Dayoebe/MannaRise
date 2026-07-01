<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Support\GrowthAnalytics;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function register()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create($validated);

        GrowthAnalytics::trackSignup(request(), $user);

        Auth::login($user);

        if (request()->hasSession()) {
            request()->session()->regenerate();
        }

        return redirect()->route('onboarding');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
