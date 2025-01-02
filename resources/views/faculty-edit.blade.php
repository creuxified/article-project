<x-layout :title="$title">
    <x-user-navbar></x-user-navbar>
    @livewire('faculty-edit', ['id' => $id])
</x-layout>