<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Terhubung ke database (ganti dengan informasi koneksi Anda)
    $db = new mysqli('localhost', 'username', 'password', 'nama_database');

    if ($db->connect_error) {
        die("Koneksi database gagal: " . $db->connect_error);
    }

    // Ambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Periksa apakah username dan password cocok
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            echo "Login sukses. Selamat datang, $username!";
        } else {
            echo "Password salah. Silakan coba lagi.";
        }
    } else {
        echo "Username tidak ditemukan. Silakan coba lagi.";
    }

    // Tutup koneksi database
    $db->close();
}
?>
