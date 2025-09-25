<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrasi Tiket Kereta Api</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .card-bg {
            background-image: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
        }
        .login-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
        }
        .toast-container {
            top: 1.5rem;
            right: 1.5rem;
        }
        /* Style untuk notifikasi saat muncul (state awal) */
        .toast-enter {
            opacity: 0;
            transform: translateX(100%);
        }
        /* Style untuk notifikasi saat sudah muncul (state akhir) */
        .toast-enter-active {
            opacity: 1;
            transform: translateX(0);
            transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
        }
        /* Style untuk notifikasi saat menghilang (state akhir) */
        .toast-exit {
            opacity: 0;
            transform: translateX(100%);
            transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-200 flex items-center justify-center min-h-screen p-4">

    <div id="toast-container" class="fixed z-50 flex flex-col items-end space-y-4 toast-container">
        @if (session('success'))
        <div id="success-toast" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-lg max-w-sm toast-enter" role="alert">
            <div class="flex items-center">
                <div class="py-1"><i class="fas fa-check-circle text-green-500 mr-3"></i></div>
                <div>
                    <p class="font-bold">Berhasil!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-100 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8 close-toast-button">
                    <span class="sr-only">Close</span>
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        @endif

        @if ($errors->any())
        <div id="error-toast" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-lg max-w-sm toast-enter" role="alert">
            <div class="flex items-center">
                <div class="py-1"><i class="fas fa-exclamation-triangle text-red-500 mr-3"></i></div>
                <div>
                    <p class="font-bold">Oops!</p>
                    <p class="text-sm">Ada masalah dengan input Anda.</p>
                </div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-100 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 close-toast-button">
                    <span class="sr-only">Close</span>
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        @endif
    </div>

    <div class="card-bg p-8 rounded-xl shadow-2xl w-full max-w-md transform transition-all duration-300">
        <div class="flex flex-col items-center">
            <i class="fas fa-user-plus text-5xl text-blue-600 mb-4 animate-pulse"></i>
            <h1 class="text-3xl font-bold mb-2 text-center text-gray-800">Buat Akun Baru</h1>
            <p class="text-gray-600 mb-6 text-center">Daftar untuk mulai memesan tiket</p>
        </div>

        <form method="POST" action="{{ route('register.post') }}" class="space-y-6">
            @csrf
            <div>
                <label for="username" class="block text-gray-700 font-semibold mb-2">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input
                        type="text"
                        id="username"
                        placeholder="Isi username"
                        name="username"
                        value="{{ old('username') }}"
                        required
                        autofocus
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-10 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 transition login-input"
                    />
                </div>
            </div>

            <div>
                <label for="nik" class="block text-gray-700 font-semibold mb-2">NIK (Nomor Induk Kependudukan)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-id-card text-gray-400"></i>
                    </div>
                    <input
                        type="text"
                        id="nik"
                        placeholder="Isi NIK"
                        name="nik"
                        value="{{ old('nik') }}"
                        required
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-10 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 transition login-input"
                    />
                </div>
            </div>

            <div>
                <label for="jenis_kelamin" class="block text-gray-700 font-semibold mb-2">Jenis Kelamin</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-venus-mars text-gray-400"></i>
                    </div>
                    <select id="jenis_kelamin" name="jenis_kelamin" required class="mt-1 block w-full rounded-lg border border-gray-300 px-10 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 transition login-input">
                        <option value="" disabled selected>Pilih Jenis Kelamin</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="email" class="block text-gray-700 font-semibold mb-2">Alamat Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input
                        type="email"
                        id="email"
                        placeholder="isi Email"
                        name="email"
                        value="{{ old('email') }}"
                        required
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
                        placeholder="..."
                        id="password"
                        name="password"
                        required
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-10 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 transition login-input"
                    />
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-gray-700 font-semibold mb-2">Konfirmasi Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-10 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 transition login-input"
                    />
                </div>
            </div>

            <input type="hidden" name="role" value="pelanggan">

            <div>
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition duration-300 shadow-md transform hover:scale-100"
                >
                    Daftar
                </button>
            </div>
        </form>

        <p class="mt-6 text-center text-gray-600">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Masuk di sini</a>
        </p>

        <a href="/" class="mt-4 block w-full text-center bg-gray-200 text-gray-700 font-bold py-2.5 rounded-lg hover:bg-gray-300 transition duration-300 shadow-md transform hover:scale-100">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
        </a>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const successToast = document.getElementById('success-toast');
            const errorToast = document.getElementById('error-toast');

            // Fungsi untuk memulai transisi masuk
            const showToast = (toast) => {
                // Hapus class 'toast-enter' dan tambahkan 'toast-enter-active' setelah jeda singkat
                setTimeout(() => {
                    toast.classList.remove('toast-enter');
                    toast.classList.add('toast-enter-active');
                }, 100); // Jeda 100ms agar transisi berjalan
            };

            // Fungsi untuk memulai transisi keluar dan menghapus elemen
            const hideToast = (toast) => {
                toast.classList.remove('toast-enter-active');
                toast.classList.add('toast-exit');
                setTimeout(() => {
                    toast.remove();
                }, 500); // Sesuai dengan durasi transisi
            };

            if (successToast) {
                showToast(successToast);
                setTimeout(() => {
                    hideToast(successToast);
                }, 5000);

                const closeButton = successToast.querySelector('.close-toast-button');
                if (closeButton) {
                    closeButton.addEventListener('click', () => {
                        hideToast(successToast);
                    });
                }
            }

            if (errorToast) {
                showToast(errorToast);
                setTimeout(() => {
                    hideToast(errorToast);
                }, 7000);

                const closeButton = errorToast.querySelector('.close-toast-button');
                if (closeButton) {
                    closeButton.addEventListener('click', () => {
                        hideToast(errorToast);
                    });
                }
            }
        });
    </script>
</body>
</html>
