<x-layout :title="$title">
    <x-user-navbar></x-user-navbar>
    @livewire('programs-edit', ['id' => $id])
</x-layout>
