<x-layout :title="$title">
    <x-user-navbar></x-user-navbar>
    @livewire('scrap-data', ['user' => $user])
</x-layout>