<x-layout :title='$title'>
    <x-user-navbar></x-user-navbar>
    @livewire('dashboard', ['user' => $user])
</x-layout>