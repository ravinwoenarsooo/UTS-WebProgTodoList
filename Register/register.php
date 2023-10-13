<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Terhubung ke database (ganti dengan informasi koneksi Anda)
    $db = new mysqli('localhost', 'username', 'password', 'nama_database');

    if ($db->connect_error) {
        die("Koneksi database gagal: " . $db->connect_error);
    }

    // Ambil data dari form
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Periksa apakah username sudah ada
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        echo "Username sudah digunakan. Silakan coba lagi.";
    } else {
        // Tambahkan pengguna baru ke database
        $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if ($db->query($query) === TRUE) {
            echo "Pendaftaran sukses. <a href='login.html'>Login</a>";
        } else {
            echo "Error: " . $query . "<br>" . $db->error;
        }
    }

    // Tutup koneksi database
    $db->close();
}
?>
