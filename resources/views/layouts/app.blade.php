<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        @livewireStyles

        @stack('css')

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            <livewire:navigation-menu />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts

        @stack('js')

        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('alert', function(message) {
                    console.log('Tipo de mensaje recibido:', typeof message, message);

                    let text = '';

                    try {
                        // Si message es directamente un objeto (no un array)
                        if (message && typeof message === 'object' && !Array.isArray(message)) {
                            if (message.message) {
                                text = message.message;
                            } else {
                                // Intentamos convertir el objeto a string de forma legible
                                text = JSON.stringify(message);
                            }
                        }
                        // Si es un array, tomamos el primer elemento
                        else if (Array.isArray(message)) {
                            text = message[0] || '';
                        }
                        // Si es un string simple
                        else if (typeof message === 'string') {
                            text = message;
                        }
                        // Cualquier otro caso, convertimos a string
                        else {
                            text = String(message);
                        }

                        // Limpiamos el mensaje de corchetes y comillas si parece un array o objeto serializado
                        if (text.startsWith('[') && text.endsWith(']')) {
                            try {
                                const parsed = JSON.parse(text);
                                if (Array.isArray(parsed) && parsed.length > 0) {
                                    text = parsed[0];
                                }
                            } catch(e) {}
                        }
                    } catch(e) {
                        console.error('Error al procesar el mensaje:', e);
                        text = 'Acción completada correctamente';
                    }

                    Swal.fire({
                        title: 'Buen trabajo!',
                        text: text,
                        icon: 'success'
                    });
                });
            });
        </script>

    </body>
</html>
