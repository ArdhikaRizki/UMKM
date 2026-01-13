<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Warung Digital' }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">

    <nav class="bg-white border-b border-gray-200 shadow-sm fixed w-full z-50 top-0 start-0">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            
            <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <span class="self-center text-2xl font-bold whitespace-nowrap text-blue-600">
                    Warung<span class="text-gray-800">Kita</span>
                </span>
            </a>

            <div class="flex md:order-2 space-x-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-4 py-2 text-center">
                        Kasir (POS)
                    </a>
                    <a href="{{ route('logout') }}" class="text-red-600 hover:text-red-800 font-medium text-sm px-4 py-2 border border-red-200 rounded-lg hover:bg-red-50">
                        Logout
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-4 py-2 text-center">
                        Login Staff
                    </a>
                @endauth
            </div>

        </div>
    </nav>

    <div class="pt-24 pb-10 px-4 max-w-screen-xl mx-auto min-h-screen">
        {{ $slot }}
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html>