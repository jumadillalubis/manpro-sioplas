<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIOPLAS')</title>
    {{-- Hubungkan Tailwind CSS di sini --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .content-area {
            min-height: calc(100vh - 4rem); /* Menyesuaikan tinggi konten */
        }
    </style>
</head>

<body class="bg-gray-50 antialiased">
    {{-- HEADER --}}
    @include('layouts.partials.header')

    <div class="flex">
        {{-- SIDEBAR --}}
        @include('layouts.partials.sidebar')

        {{-- MAIN CONTENT --}}
        <main class="flex-1 overflow-x-hidden overflow-y-auto content-area flex flex-col">
            {{-- Konten halaman --}}
            <div class="p-6 flex-grow">
                @yield('content')
            </div>

            {{-- FOOTER --}}
            @include('layouts.partials.footer')
        </main>
    </div>
</body>

</html>
