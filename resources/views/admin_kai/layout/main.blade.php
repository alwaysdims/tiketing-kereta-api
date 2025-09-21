<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title }} - Tiketing Kereta Api</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        /* Optional: smooth dropdown transition */
        .dropdown-transition {
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

    </style>
</head>

<body class="bg-gray-100">
    @include('admin_kai.layout.navbar')


    <div class="pt-16 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            @yield('content')
        </div>
    </div>

    @include('admin_kai.layout.footer')
</body>
</html>
