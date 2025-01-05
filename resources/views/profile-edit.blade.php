<x-layout-landing :title="'Profile Edit - UNS Citation Management'">
    <x-guest-navbar></x-guest-navbar>
    @livewire('profile-edit', ['user' => $user])
</x-layout-landing>
