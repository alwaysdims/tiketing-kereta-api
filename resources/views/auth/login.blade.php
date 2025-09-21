<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login Tiket Kereta Api</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .card-bg {
            background-image: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
        }
        .login-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
        }
    </style>
</head>
<body class="bg-gray-200 flex items-center justify-center min-h-screen p-4">
    <div class="card-bg p-8 rounded-xl shadow-2xl w-full max-w-sm transform transition-all duration-300 hover:scale-105">
        <div class="flex flex-col items-center">
            <i class="fas fa-train text-5xl text-blue-600 mb-4 animate-bounce"></i>
            <h1 class="text-3xl font-bold mb-2 text-center text-gray-800">Selamat Datang!</h1>
            <p class="text-gray-600 mb-6 text-center">Silakan masuk untuk melanjutkan</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span class="block sm:inline"> Ada masalah dengan input Anda.</span>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="loginForm" method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            <div>
                <label for="login" class="block text-gray-700 font-semibold mb-2">Username atau Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input
                        type="text"
                        id="login"
                        name="login"
                        value="{{ old('login') }}"
                        required
                        autofocus
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-10 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 transition login-input"
                    />
                </div>
            </div>

            <div>
                <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-10 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 transition login-input"
                    />
                </div>
            </div>

            <div>
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition duration-300 shadow-md transform hover:scale-100"
                >
                    Masuk
                </button>
            </div>
        </form>

        <p class="mt-6 text-center text-gray-600">
            Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">Daftar sekarang</a>
        </p>
    </div>
</body>
</html>
