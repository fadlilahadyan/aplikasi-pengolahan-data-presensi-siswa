<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Laporan Perkembangan - TKIT Fathurrobbany</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    <aside class="w-72 bg-white border-r border-gray-200 flex flex-col">
        <div class="p-6 flex items-center gap-3 border-b border-gray-100">
            <div class="bg-teal-700 text-white p-2 rounded-lg text-xl">
                <i class="fas fa-school"></i>
            </div>
            <div>
                <h1 class="font-bold text-gray-800 text-lg leading-tight">TKIT Fathurrobbany</h1>
                <p class="text-xs text-gray-500">Laporan Perkembangan Siswa</p>
            </div>
        </div>

        <div class="p-5">
            <div class="bg-teal-50 rounded-xl p-4 border border-teal-100">
                <p class="text-sm text-gray-600 mb-1">Selamat datang,</p>
                <p class="font-semibold text-gray-800">Ustadzah Enok Hasanah</p>
            </div>
        </div>

        <nav class="flex-1 px-4">
            <a href="#" class="flex items-center gap-3 text-gray-700 hover:bg-gray-50 bg-gray-50 px-4 py-3 rounded-lg font-medium">
                <i class="far fa-file-alt text-lg"></i>
                Laporan Perkembangan
            </a>
        </nav>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="bg-white px-8 py-4 flex justify-end border-b border-gray-200 sticky top-0 z-10">
            <a href="logout.php" class="text-gray-600 hover:text-red-600 flex items-center gap-2 font-medium">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </a>
        </header>

        <div class="p-8 max-w-5xl mx-auto w-full">
            
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-1">Input Laporan Perkembangan</h2>
                    <p class="text-gray-500">Kelas: <span class="font-semibold text-gray-700">TK B1</span></p>
                </div>
                <div class="bg-blue-50 text-blue-600 p-3 rounded-lg text-2xl">
                    <i class="fas fa-file-invoice"></i>
                </div>
            </div>

            <form action="proses_laporan.php" method="POST" class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Siswa</label>
                    <select name="id_siswa" class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 appearance-none bg-white">
                        <option value="">-- Pilih Siswa --</option>
                        <option value="S001">Ahmad Budi</option>
                        <option value="S002">Siti Aisyah</option>
                    </select>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Laporan</label>
                    <input type="date" name="tanggal" value="2026-03-01" class="border border-gray-300 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-500 w-64">
                </div>

                <div id="aspek-container">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-sm font-medium text-gray-700">Aspek Perkembangan</h3>
                        <button type="button" onclick="tambahAspek()" class="text-teal-600 hover:text-teal-800 font-medium text-sm flex items-center gap-1">
                            <i class="fas fa-plus"></i> Tambah Aspek
                        </button>
                    </div>

                    <div class="aspek-box border border-gray-200 rounded-xl p-5 mb-4 bg-gray-50">
                        <h4 class="text-sm font-semibold text-gray-800 mb-3 aspek-title">Aspek 1</h4>
                        <div class="mb-4">
                            <select name="aspek[]" class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white">
                                <option value="Kognitif">Kognitif</option>
                                <option value="Motorik Kasar">Motorik Kasar</option>
                                <option value="Motorik Halus">Motorik Halus</option>
                                <option value="Sosial Emosional">Sosial Emosional</option>
                                <option value="Agama & Moral">Agama & Moral</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Perkembangan</label>
                            <textarea name="deskripsi[]" rows="4" class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-500 placeholder-gray-400" placeholder="Tulis deskripsi perkembangan siswa..."></textarea>
                        </div>
                    </div>
                </div> <div class="flex justify-end mt-8 border-t border-gray-100 pt-6">
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center gap-2 shadow-sm">
                        <i class="fas fa-save"></i> Simpan Laporan
                    </button>
                </div>
            </form>

        </div>
    </main>

    <script>
        let aspekCount = 1;
        function tambahAspek() {
            aspekCount++;
            const container = document.getElementById('aspek-container');
            const kotakBaru = document.createElement('div');
            kotakBaru.className = 'aspek-box border border-gray-200 rounded-xl p-5 mb-4 bg-gray-50';
            kotakBaru.innerHTML = `
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-sm font-semibold text-gray-800 aspek-title">Aspek ${aspekCount}</h4>
                    <button type="button" onclick="hapusAspek(this)" class="text-red-500 hover:text-red-700 text-xs font-medium"><i class="fas fa-trash"></i> Hapus</button>
                </div>
                <div class="mb-4">
                    <select name="aspek[]" class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white">
                        <option value="Kognitif">Kognitif</option>
                        <option value="Motorik Kasar">Motorik Kasar</option>
                        <option value="Motorik Halus">Motorik Halus</option>
                        <option value="Sosial Emosional">Sosial Emosional</option>
                        <option value="Agama & Moral">Agama & Moral</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Perkembangan</label>
                    <textarea name="deskripsi[]" rows="4" class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-500 placeholder-gray-400" placeholder="Tulis deskripsi perkembangan siswa..."></textarea>
                </div>
            `;
            container.appendChild(kotakBaru);
        }

        function hapusAspek(button) {
            const kotak = button.closest('.aspek-box');
            kotak.remove();
            // Opsional: Bikin nomor aspek urut lagi (jika diperlukan)
        }
    </script>
</body>
</html>