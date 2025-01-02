<x-layout :title="$title">
    <x-user-navbar></x-user-navbar>
    @livewire('user-profile-edit', ['user' => $user])
</x-layout>