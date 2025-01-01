<x-layout :title='$title'>
    <x-user-navbar></x-user-navbar>
    @livewire('user-database', ['user' => $user])
</x-layout>