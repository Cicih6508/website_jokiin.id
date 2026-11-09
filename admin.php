<?php
// Sertakan koneksi database
include 'koneksi.php';

// Ambil semua data order dari database, gabungkan dengan data user
$query = "SELECT orders.*, users.nama, users.no_wa 
          FROM orders 
          JOIN users ON orders.user_id = users.id 
          ORDER BY orders.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — Jokiin.ID</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; background: #F8F9FA; }</style>
</head>
<body class="p-6 md:p-10">

    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Dashboard Admin Jokiin.ID</h1>
                <p class="text-sm text-slate-500">Kelola dan pantau daftar tugas masuk dari klien di sini.</p>
            </div>
            <a href="index.html" target="_blank" class="inline-flex items-center justify-center bg-teal-600 text-white font-medium px-4 py-2 rounded-xl text-sm hover:bg-teal-700 transition">Lihat Website Utama</a>
        </div>

        <!-- Tabel Data Order -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wider border-b border-slate-200">
                            <th class="p-4 font-semibold">Waktu / ID</th>
                            <th class="p-4 font-semibold">Klien & No. WA</th>
                            <th class="p-4 font-semibold">Layanan & Detail</th>
                            <th class="p-4 font-semibold">Deadline</th>
                            <th class="p-4 font-semibold">File Soal</th>
                            <th class="p-4 font-semibold">Status</th>
                            <th class="p-4 font-semibold text-center">Aksi WA</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="p-4 align-top">
                                        <span class="font-bold text-slate-800">#ORD-<?= $row['id'] ?></span>
                                        <div class="text-xs text-slate-400 mt-0.5"><?= $row['created_at'] ?></div>
                                    </td>
                                    <td class="p-4 align-top">
                                        <div class="font-semibold text-slate-800"><?= htmlspecialchars($row['nama']) ?></div>
                                        <a href="https://wa.me/<?= $row['no_wa'] ?>" target="_blank" class="text-teal-600 hover:underline text-xs font-medium inline-block mt-0.5">
                                            <?= $row['no_wa'] ?> ↗
                                        </a>
                                    </td>
                                    <td class="p-4 align-top max-w-xs">
                                        <div class="font-medium text-teal-700"><?= htmlspecialchars($row['kategori_layanan']) ?></div>
                                        <div class="text-xs text-slate-500 mt-1 line-clamp-2"><?= htmlspecialchars($row['deskripsi']) ?></div>
                                    </td>
                                    <td class="p-4 align-top text-xs font-medium text-slate-600">
                                        <?= date('d M Y, H:i', strtotime($row['deadline'])) ?>
                                    </td>
                                    <td class="p-4 align-top">
                                        <?php if (!empty($row['file_tugas'])): ?>
                                            <a href="uploads/<?= $row['file_tugas'] ?>" download class="inline-flex items-center gap-1 text-xs bg-slate-100 hover:bg-teal-50 hover:text-teal-700 text-slate-700 px-2.5 py-1.5 rounded-lg font-medium transition border border-slate-200">
                                                📥 Download
                                            </a>
                                        <?php else: ?>
                                            <span class="text-xs text-slate-400 italic">Tanpa file</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4 align-top">
                                        <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold 
                                            <?= $row['status'] == 'Pending' ? 'bg-amber-100 text-amber-800' : 'bg-teal-100 text-teal-800' ?>">
                                            <?= $row['status'] ?>
                                        </span>
                                    </td>
                                    <td class="p-4 align-top text-center">
                                        <a href="https://wa.me/<?= $row['no_wa'] ?>?text=Halo%20<?= urlencode($row['nama']) ?>,%20terkait%20pesanan%20tugas%20<?= urlencode($row['kategori_layanan']) ?>%20di%20Jokiin.ID..." 
                                           target="_blank" 
                                           class="inline-flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white p-2 rounded-xl text-xs font-medium transition shadow-sm"
                                           title="Chat Klien">
                                            💬 Chat WA
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400 italic">Belum ada pesanan tugas yang masuk ke database.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>