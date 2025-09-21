<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {{ $title }} | Tiketing Kereta Api</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style type="text/tailwindcss">
        /* Atur warna dasar di sini jika perlu */
        .primary { color: #2563eb; } /* blue-600 */
        .active-link { background-color: #2563eb; color: white; }
    </style>

</head>
<body class="bg-gray-100 font-sans">

    @include('layout.navbar')

    <div class="h-16"></div>

    <main class="container mx-auto px-4 py-8">
        @yield('content')

    </main>

    @include('layout.footer')

</body>
</html>
