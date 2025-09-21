@extends('admin_kai.layout.main', ['title' => 'Users Admin'])

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between mb-6">
        <h1 class="text-2xl font-bold">Data Admin</h1>
        <button onclick="showModal('addModal')" class="bg-blue-600 text-white px-4 py-2 rounded">
            + Tambah Admin
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Nama Admin</th>
                    <th class="px-4 py-2">NIP</th>
                    <th class="px-4 py-2">Username</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Jenis Kelamin</th>
                    <th class="px-4 py-2">Jabatan</th>
                    <th class="px-4 py-2">No HP</th>
                    <th class="px-4 py-2">Alamat</th>
                    <th class="px-4 py-2">Shift</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $i => $admin)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $admins->firstItem() + $i }}</td>
                        <td class="px-4 py-2">{{ $admin->nama_admin ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $admin->nip ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $admin->user->username ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $admin->user->email ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $admin->user->jenis_kelamin == 'L' ? 'Laki-laki' : ($admin->user->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</td>
                        <td class="px-4 py-2">{{ $admin->jabatan ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $admin->no_hp ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $admin->alamat ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $admin->shift ?? '-' }}</td>
                        <td class="px-4 py-2">
                            <div class="flex space-x-2">
                                <!-- Detail -->
                                <button type="button" onclick='showDetail(@json($admin))' class="text-blue-500 hover:text-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <!-- Edit -->
                                <button type="button" onclick='showEdit(@json($admin))' class="text-green-500 hover:text-green-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </button>
                                <!-- Delete -->
                                <form action="{{ route('admin.users.admin.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center py-4">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $admins->links() }}
    </div>
</div>

<!-- Modal Tambah -->
<div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Tambah Admin Baru</h2>
            <form method="POST" action="{{ route('admin.users.admin.store') }}">
                @csrf
                <div class="mb-3">
                    <label>Nama Admin *</label>
                    <input type="text" name="nama_admin" required class="w-full border rounded px-2 py-1">
                </div>
                <div class="mb-3">
                    <label>NIP *</label>
                    <input type="text" name="nip" required class="w-full border rounded px-2 py-1">
                </div>
                <div class="mb-3">
                    <label>Jabatan *</label>
                    <input type="text" name="jabatan" required class="w-full border rounded px-2 py-1">
                </div>
                <div class="mb-3">
                    <label>No HP</label>
                    <input type="text" name="no_hp" class="w-full border rounded px-2 py-1">
                </div>
                <div class="mb-3">
                    <label>Alamat</label>
                    <textarea name="alamat" class="w-full border rounded px-2 py-1"></textarea>
                </div>
                <div class="mb-3">
                    <label>Shift</label>
                    <select name="shift" class="w-full border rounded px-2 py-1">
                        <option value="">Pilih</option>
                        <option value="pagi">Pagi</option>
                        <option value="siang">Siang</option>
                        <option value="malam">Malam</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Username *</label>
                    <input type="text" name="username" required class="w-full border rounded px-2 py-1">
                </div>
                <div class="mb-3">
                    <label>Email *</label>
                    <input type="email" name="email" required class="w-full border rounded px-2 py-1">
                </div>
                <div class="mb-3">
                    <label>NIK *</label>
                    <input type="text" name="nik" required class="w-full border rounded px-2 py-1">
                </div>
                <div class="mb-3">
                    <label>Password *</label>
                    <input type="password" name="password" required class="w-full border rounded px-2 py-1">
                </div>
                <div class="mb-3">
                    <label>Konfirmasi Password *</label>
                    <input type="password" name="password_confirmation" required class="w-full border rounded px-2 py-1">
                </div>
                <div class="mb-3">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full border rounded px-2 py-1">
                        <option value="">Pilih</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" onclick="hideModal('addModal')" class="px-3 py-1 border rounded">Batal</button>
                    <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Edit Admin</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editId">
                <div class="mb-3">
                    <label>Nama Admin *</label>
                    <input type="text" name="nama_admin" id="editNamaAdmin" required class="w-full border rounded px-2 py-1">
                </div>
                <div class="mb-3">
                    <label>NIP *</label>
                    <input type="text" name="nip" id="editNip" required class="w-full border rounded px-2 py-1">
                </div>
                <div class="mb-3">
                    <label>Jabatan *</label>
                    <input type="text" name="jabatan" id="editJabatan" required class="w-full border rounded px-2 py-1">
                </div>
                <div class="mb-3">
                    <label>No HP</label>
                    <input type="text" name="no_hp" id="editNoHp" class="w-full border rounded px-2 py-1">
                </div>
                <div class="mb-3">
                    <label>Alamat</label>
                    <textarea name="alamat" id="editAlamat" class="w-full border rounded px-2 py-1"></textarea>
                </div>
                <div class="mb-3">
                    <label>Shift</label>
                    <select name="shift" id="editShift" class="w-full border rounded px-2 py-1">
                        <option value="">Pilih</option>
                        <option value="pagi">Pagi</option>
                        <option value="siang">Siang</option>
                        <option value="malam">Malam</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Username *</label>
                    <input type="text" name="username" id="editUsername" required class="w-full border rounded px-2 py-1">
                </div>
                <div class="mb-3">
                    <label>Email *</label>
                    <input type="email" name="email" id="editEmail" required class="w-full border rounded px-2 py-1">
                </div>
                <div class="mb-3">
                    <label>NIK *</label>
                    <input type="text" name="nik" id="editNik" required class="w-full border rounded px-2 py-1">
                </div>
                <div class="mb-3">
                    <label>Password (opsional)</label>
                    <input type="password" name="password" id="editPassword" class="w-full border rounded px-2 py-1">
                </div>
                <div class="mb-3">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="editPasswordConfirm" class="w-full border rounded px-2 py-1">
                </div>
                <div class="mb-3">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="editJenisKelamin" class="w-full border rounded px-2 py-1">
                        <option value="">Pilih</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" onclick="hideModal('editModal')" class="px-3 py-1 border rounded">Batal</button>
                    <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm md:max-w-md my-10 transform transition-all duration-300 scale-95 hover:scale-100 ease-out">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-5">Detail Admin</h2>
            <div class="space-y-3 text-gray-700">
                <p><strong>Nama:</strong> <span id="detailNama" class="font-medium"></span></p>
                <p><strong>NIP:</strong> <span id="detailNip" class="font-medium"></span></p>
                <p><strong>Username:</strong> <span id="detailUsername" class="font-medium"></span></p>
                <p><strong>Email:</strong> <span id="detailEmail" class="font-medium"></span></p>
                <p><strong>Jenis Kelamin:</strong> <span id="detailJenisKelamin" class="font-medium"></span></p>
                <p><strong>Jabatan:</strong> <span id="detailJabatan" class="font-medium"></span></p>
                <p><strong>No HP:</strong> <span id="detailNoHp" class="font-medium"></span></p>
                <p><strong>Alamat:</strong> <span id="detailAlamat" class="font-medium"></span></p>
                <p><strong>Shift:</strong> <span id="detailShift" class="font-medium"></span></p>
            </div>
            <div class="flex justify-end mt-6">
                <button type="button" onclick="hideModal('detailModal')" class="px-5 py-2 text-sm font-semibold text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50 transition-colors duration-200">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        modal.classList.remove('hidden');
    }

    function hideModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        modal.classList.add('hidden');
    }

    function showEdit(admin) {
        document.getElementById('editId').value = admin.id;
        document.getElementById('editNamaAdmin').value = admin.nama_admin ?? '';
        document.getElementById('editNip').value = admin.nip ?? '';
        document.getElementById('editJabatan').value = admin.jabatan ?? '';
        document.getElementById('editNoHp').value = admin.no_hp ?? '';
        document.getElementById('editAlamat').value = admin.alamat ?? '';
        document.getElementById('editShift').value = admin.shift ?? '';
        document.getElementById('editUsername').value = admin.user?.username ?? '';
        document.getElementById('editEmail').value = admin.user?.email ?? '';
        document.getElementById('editNik').value = admin.user?.nik ?? '';
        document.getElementById('editJenisKelamin').value = admin.user?.jenis_kelamin ?? '';
        document.getElementById('editForm').action = `/admin/users/admin/${admin.id}`;
        showModal('editModal');
    }

    function showDetail(admin) {
        document.getElementById('detailNama').innerText = admin.nama_admin ?? '-';
        document.getElementById('detailNip').innerText = admin.nip ?? '-';
        document.getElementById('detailUsername').innerText = admin.user?.username ?? '-';
        document.getElementById('detailEmail').innerText = admin.user?.email ?? '-';
        document.getElementById('detailJenisKelamin').innerText = admin.user?.jenis_kelamin === 'L' ? 'Laki-laki' : (admin.user?.jenis_kelamin === 'P' ? 'Perempuan' : '-');
        document.getElementById('detailJabatan').innerText = admin.jabatan ?? '-';
        document.getElementById('detailNoHp').innerText = admin.no_hp ?? '-';
        document.getElementById('detailAlamat').innerText = admin.alamat ?? '-';
        document.getElementById('detailShift').innerText = admin.shift ?? '-';
        showModal('detailModal');
    }
</script>
@endsection
