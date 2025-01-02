<x-layout :title='$title'>
    @livewire('request-role', ['user' => $user])
    @livewire('history-logs')
</x-layout>
