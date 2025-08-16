<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
};
?>

<div class="flex items-center justify-center min-h-screen bg-gray-900">
    <div class="w-full max-w-md p-8 space-y-6 bg-gray-800 rounded-2xl shadow-lg">
        
        <!-- Heading -->
        <h2 class="text-2xl font-bold text-center text-white">Welcome Back</h2>
        <p class="text-sm text-gray-400 text-center">Login to continue to your dashboard</p>

        <!-- Session Status -->
        @if (session('status'))
            <div class="p-3 text-sm text-green-400 bg-green-900/40 border border-green-700 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit="login" class="space-y-5">
            
            <!-- Email -->
            <div>
                <label for="email" class="block mb-1 text-gray-300 font-medium">Email</label>
                <input wire:model="form.email" id="email" type="email"
                       class="w-full px-4 py-2 text-gray-200 bg-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                       required autofocus autocomplete="username">

                @error('form.email')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block mb-1 text-gray-300 font-medium">Password</label>
                <input wire:model="form.password" id="password" type="password"
                       class="w-full px-4 py-2 text-gray-200 bg-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                       required autocomplete="current-password">

                @error('form.password')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me + Forgot Password -->
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center text-gray-400">
                    <input wire:model="form.remember" id="remember" type="checkbox"
                           class="w-4 h-4 text-indigo-600 bg-gray-700 border-gray-600 rounded focus:ring-indigo-500">
                    <span class="ml-2">Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate
                       class="text-indigo-400 hover:text-indigo-300">Forgot password?</a>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full py-2 text-white bg-indigo-600 rounded-lg hover:bg-indigo-500 transition">
                Log in
            </button>
        </form>

        <!-- Register Redirect -->
        @if (Route::has('register'))
            <p class="text-sm text-center text-gray-400">
                Don’t have an account?
                <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 font-medium">Sign up</a>
            </p>
        @endif
    </div>
</div>
