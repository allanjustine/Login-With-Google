@extends('layouts.bs4-template')
@section('container')
    <div class="row">
        <div class="col-6 offset-3">
            <x-form-card method="post" action="/login">
                @if ($errors->any() && $retries > 0)
                    <x-alert type="warning">
                        Remaining {{ $retries }} attempt.
                    </x-alert>
                @endif

                @if ($retries <= 0)
                    @php
                        $remainingTime = \Carbon\CarbonInterval::seconds($seconds)->cascade();
                        $formattedTime = $remainingTime->forHumans(['short' => true]);
                    @endphp

                    <x-alert type="danger">
                        Please try again after {{ $formattedTime }}.
                    </x-alert>
                @endif


                <x-guest-layout>
                    <x-authentication-card>
                        <x-slot name="logo">
                            <x-authentication-card-logo />
                        </x-slot>

                        <x-validation-errors class="mb-4" />

                        @if (session('status'))
                            <div class="mb-4 text-sm font-medium text-green-600">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="mb-4 text-sm font-medium text-red-600">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div>
                                <x-label for="email" value="{{ __('Email') }}" />
                                <x-input id="email" class="block w-full mt-1" type="email" name="email"
                                    :value="old('email')" required autofocus autocomplete="username" />
                            </div>

                            <div class="mt-4">
                                <x-label for="password" value="{{ __('Password') }}" />
                                <x-input id="password" class="block w-full mt-1" type="password" name="password" required
                                    autocomplete="current-password" />
                            </div>

                            <div class="block mt-4">
                                <label for="remember_me" class="flex items-center">
                                    <x-checkbox id="remember_me" name="remember" />
                                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                                </label>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                @if (Route::has('password.request'))
                                    <a class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        href="{{ route('password.request') }}">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                @endif

                                <x-button class="ml-4">
                                    {{ __('Log in') }}
                                </x-button>
                            </div>
                            {{-- Laracoding Login with Google Demo --}}
                            <div class="block mt-4">
                                <div class="flex items-center justify-center mt-4">
                                    <a href="{{ url('auth/google') }}">
                                        <img
                                            src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png">
                                    </a>
                                </div>
                            </div>
                        </form>
                    </x-authentication-card>
                </x-guest-layout>
            </x-form-card>
        </div>
    </div>
@endsection
