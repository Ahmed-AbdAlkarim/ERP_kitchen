<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use function Livewire\Volt\form;
use function Livewire\Volt\layout;

layout('layouts.guest');
form(LoginForm::class);

$login = function () {
    $this->validate();
    $this->form->authenticate();
    Session::regenerate();
    $this->redirectIntended(route('dashboard', absolute: false), navigate: false);
};

?>

<div>
    <!-- Title -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-white tracking-wide mb-2">
            Welcome Back
        </h2>
        <p class="text-white/70 text-sm">
            Sign in to your account
        </p>
    </div>

    <div class="border-t border-white/20 my-8"></div>

    <form wire:submit="login" class="space-y-6">

        <!-- Email -->
        <div class="space-y-2">
            <label class="block text-sm font-semibold text-white">
                Email
            </label>
            <div class="relative">
           
                <input
                    wire:model="form.email"
                    type="email"
                    required
                    autofocus
                    class="input-field w-full pr-10 pl-4 py-3.5 rounded-lg
                           bg-white/10 border border-white/30
                           text-white placeholder-white/50 focus:outline-none"
                    placeholder="Enter your email"
                >
            </div>
            <x-input-error :messages="$errors->get('form.email')" class="text-red-300 text-xs"/>
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <label class="block text-sm font-semibold text-white">
                Password
            </label>
            <div class="relative">
           
                <input
                    wire:model="form.password"
                    type="password"
                    required
                    class="input-field w-full pr-10 pl-4 py-3.5 rounded-lg
                           bg-white/10 border border-white/30
                           text-white placeholder-white/50 focus:outline-none"
                    placeholder="Enter your password"
                >
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="text-red-300 text-xs"/>
        </div>

        

        <!-- Button -->
        <div class="pt-4">
            <button type="submit"
                    class="btn-primary w-full py-4 rounded-xl
                           text-white font-bold tracking-wide
                           shadow-lg hover:shadow-2xl"
                    wire:loading.attr="disabled">

                <span wire:loading.remove>Log in</span>

                <span wire:loading class="flex justify-center items-center gap-2">
                    <span class="animate-spin">⏳</span>
                    جاري الدخول...
                </span>
            </button>
        </div>

    </form>
</div>
