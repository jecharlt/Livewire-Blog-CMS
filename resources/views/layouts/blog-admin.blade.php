<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <livewire:blog-admin.components.header/>
    <body class="antialiased">
        <div class="w-100">
            {{ $slot }}
        </div>
        <x-livewire-bootstrap-toaster::toast/>
        @livewireScripts
    </body>
</html>
