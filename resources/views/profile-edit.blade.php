<x-layout-landing :title='$title'>
    <x-guest-navbar></x-guest-navbar>
    @livewire('profile-edit', ['user' => $user])
</x-layout-landing>