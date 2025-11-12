<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Bongkar Muat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="flex flex-col min-h-screen">
    
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <footer class="w-full text-center py-4 text-sm text-gray-600 bg-white shadow-inner border-t border-gray-200">
        © {{ date('Y') }} PT. CBA Chemical Industry. Hak Cipta Dilindungi.
    </footer>

    @livewireScripts
</body>
</html>