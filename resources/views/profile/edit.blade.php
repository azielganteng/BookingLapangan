@extends('layouts.layout')

@section('title', 'Profile')

@section('content')

<div class="space-y-6 max-w-4xl mx-auto">

    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-zinc-900">Profile</h1>
        <p class="text-zinc-500 text-xs sm:text-sm mt-1">Kelola informasi akun dan keamanan kamu</p>
    </div>

    <div class="space-y-5">

        {{-- Update Profile Info --}}
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-xs p-4 sm:p-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        {{-- Update Password --}}
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-xs p-4 sm:p-6">
            @include('profile.partials.update-password-form')
        </div>

        {{-- Delete Account --}}
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-xs p-4 sm:p-6">
            @include('profile.partials.delete-user-form')
        </div>

    </div>
</div>

@endsection