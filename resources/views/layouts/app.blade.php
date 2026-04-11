<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Ticket System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased">

    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('tickets.index') }}"
                            class="flex items-center text-xl font-bold text-indigo-600">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo"
                                class="h-[62px] w-[62px] mr-2 object-contain">
                            IT Support
                        </a>
                    </div>
                    <div class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-8">
                        @auth
                            <a href="{{ route('tickets.index') }}"
                                class="{{ request()->routeIs('tickets.index') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Dashboard
                            </a>
                            @if(auth()->user()->isSuperAdmin())
                                <a href="{{ route('users.index') }}"
                                    class="{{ request()->routeIs('users.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    User Management
                                </a>
                            @endif
                        @endauth
                        <a href="{{ route('tickets.create') }}"
                            class="{{ request()->routeIs('tickets.create') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            New Ticket
                        </a>
                        <a href="{{ route('tickets.track.form') }}"
                            class="{{ request()->routeIs('tickets.track.form') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Track Ticket
                        </a>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    @auth
                        <a href="{{ route('logout') }}" class="text-gray-500 hover:text-gray-700 font-medium text-sm">Log
                            Out</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 font-medium text-sm">Log
                            in</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="py-10">
        <main>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="rounded-md bg-green-50 p-4 mb-6 mx-4 sm:mx-0">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

</body>

</html>