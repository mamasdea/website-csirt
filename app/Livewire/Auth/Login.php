<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

#[Title('Login')]
#[Layout('components.layouts-backend.app-login')]

class Login extends Component
{
    public string $email = '';
    public string $password = '';

    protected array $rules = [
        'email' => ['required', 'email'],
        'password' => ['required'],
    ];

    public function login()
    {
        $this->validate();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            Session::flash('error', 'The provided credentials do not match our records.');
            return;
        }

        return redirect('/dashboard');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
