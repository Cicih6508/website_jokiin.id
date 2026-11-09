<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama     = mysqli_real_escape_string($conn, $_POST['fName']);
    $no_wa    = mysqli_real_escape_string($conn, $_POST['fWa']);
    $instansi = mysqli_real_escape_string($conn, $_POST['fSchool']);
    $layanan  = mysqli_real_escape_string($conn, $_POST['fService']);
    $detail   = mysqli_real_escape_string($conn, $_POST['fDetail']);
    $deadline = mysqli_real_escape_string($conn, $_POST['fDeadline']);
    $express  = mysqli_real_escape_string($conn, $_POST['fExpress']);

    $query_cek = mysqli_query($conn, "SELECT id FROM users WHERE no_wa = '$no_wa'");
    if (mysqli_num_rows($query_cek) > 0) {
        $row = mysqli_fetch_assoc($query_cek);
        $user_id = $row['id'];
    } else {
        $insert_user = mysqli_query($conn, "INSERT INTO users (nama, no_wa) VALUES ('$nama', '$no_wa')");
        $user_id = mysqli_insert_id($conn);
    }

    $nama_file_db = "";
    if (isset($_FILES['fFiles']) && $_FILES['fFiles']['error'][0] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_name = time() . '_' . basename($_FILES['fFiles']['name'][0]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES['fFiles']['tmp_name'][0], $target_file)) {
            $nama_file_db = $file_name;
        }
    }

    // 3. Simpan data pesanan ke tabel 'orders'
    $kategori_lengkap = $layanan . " (" . ($instansi ? $instansi : "Umum") . " - " . $express . ")";
    $insert_order = mysqli_query($conn, "INSERT INTO orders (user_id, kategori_layanan, deskripsi, deadline, file_tugas, status) 
                    VALUES ('$user_id', '$kategori_lengkap', '$detail', '$deadline', '$nama_file_db', 'Pending')");

    if ($insert_order) {
        // 4. Kirim Notifikasi Email Otomatis ke Email Admin
        $to = "ciciparamita6508@gmail.com"; // Ganti dengan email lo
        $subject = "[PESANAN BARU] Jokiin.ID - " . $nama;
        $message = "Ada pesanan tugas baru masuk:\n\n";
        $message .= "Nama: $nama\n";
        $message .= "No WA: $no_wa\n";
        $message .= "Instansi: $instansi\n";
        $message .= "Layanan: $layanan ($express)\n";
        $message .= "Deadline: $deadline\n";
        $message .= "Detail:\n$detail\n";
        if($nama_file_db) {
            $message .= "File Pendukung: uploads/$nama_file_db\n";
        }
        
        $headers = "From: no-reply@jokiin.id";
        @mail($to, $subject, $message, $headers);

        // Berikan respon sukses
        echo json_encode(["status" => "success", "message" => "Order berhasil disimpan!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menyimpan ke database."]);
    }
}
?>