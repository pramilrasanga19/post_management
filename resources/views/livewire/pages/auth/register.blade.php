<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
};
?>

<div class="flex items-center justify-center min-h-screen bg-gray-900">
    <div class="w-full max-w-md p-8 space-y-6 bg-gray-800 rounded-2xl shadow-lg">
        
        <!-- Heading -->
        <h2 class="text-2xl font-bold text-center text-white">Create Account</h2>
        <p class="text-sm text-gray-400 text-center">Register to get started with your dashboard</p>

        <form wire:submit="register" class="space-y-5">
            
            <!-- Name -->
            <div>
                <label for="name" class="block mb-1 text-gray-300 font-medium">Name</label>
                <input wire:model="name" id="name" type="text"
                       class="w-full px-4 py-2 text-gray-200 bg-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                       required autofocus autocomplete="name">

                @error('name')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block mb-1 text-gray-300 font-medium">Email</label>
                <input wire:model="email" id="email" type="email"
                       class="w-full px-4 py-2 text-gray-200 bg-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                       required autocomplete="username">

                @error('email')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block mb-1 text-gray-300 font-medium">Password</label>
                <input wire:model="password" id="password" type="password"
                       class="w-full px-4 py-2 text-gray-200 bg-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                       required autocomplete="new-password">

                @error('password')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block mb-1 text-gray-300 font-medium">Confirm Password</label>
                <input wire:model="password_confirmation" id="password_confirmation" type="password"
                       class="w-full px-4 py-2 text-gray-200 bg-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                       required autocomplete="new-password">

                @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full py-2 text-white bg-indigo-600 rounded-lg hover:bg-indigo-500 transition">
                Register
            </button>
        </form>

        <!-- Redirect to Login -->
        @if (Route::has('login'))
            <p class="text-sm text-center text-gray-400">
                Already have an account?
                <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-medium" wire:navigate>
                    Log in
                </a>
            </p>
        @endif
    </div>
</div>
