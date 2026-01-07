<?php

// to make livewire u should install livewire first in the terminal
// composer require livewire/livewire #kiw


namespace App\Livewire\Auth;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $pass = '';

    public function login(){
        $this->validate([
            'email'=>'required|email',
            'pass'=>'required',
        ]);

        if (Auth::attempt(['email'=> $this->email, 'password'=> $this->pass])){

            session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        $this->addError('email','email atau password salah');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
