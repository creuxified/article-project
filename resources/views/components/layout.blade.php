<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
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
    @vite('resources/css/app.css')
    @livewireStyles()
</head>

<body>
    {{ $slot }}
</body>

</html>
