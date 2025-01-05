<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Manajemen Sitasi UNS' }}</title>
    <link rel="icon" href="{{ asset('images/logo_UNS.png') }}" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modalToggleButton = document.querySelector('[data-modal-toggle="crud-modal"]');
            const modal = document.getElementById('crud-modal');

            // Toggle modal visibility
            modalToggleButton.addEventListener('click', () => {
                modal.classList.toggle('hidden');
            });

            // Close modal when clicking the close button
            const closeButton = modal.querySelector('[data-modal-toggle="crud-modal"]');
            closeButton.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });
    </script>
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('alert', (data) => {
                Swal.fire({
                    icon: data.type, // 'success', 'error', 'warning', etc.
                    title: 'Notification',
                    text: data.message,
                    confirmButtonText: 'OK',
                });
            });
        });
    </script>
    @vite('resources/css/app.css')
    @livewireStyles()
</head>

<body>
    {{-- through here... --}}
    {{ $slot }}
    @livewireScripts
</body>

</html>

{{-- this is our layout and every page will be rendered inside of it.. --}}
