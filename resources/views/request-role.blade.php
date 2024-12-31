<x-layout :title='$title'>
    <x-user-navbar></x-user-navbar>
    @livewire('request-role', ['user' => $user])
    @livewire('history-logs')
</x-layout>