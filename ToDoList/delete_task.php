<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $task_id = $_GET['id'];
    // Koneksi ke database (ganti dengan informasi koneksi Anda)
    $db = new mysqli('localhost', 'username', 'password', 'nama_database');

    if ($db->connect_error) {
        die("Koneksi database gagal: " . $db->connect_error);
    }

    $user_id = $_SESSION['user_id'];

    // Periksa apakah tugas tersebut milik pengguna yang sedang login
    $query = "SELECT * FROM tasks WHERE id = ? AND user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Tugas tidak ditemukan atau bukan milik Anda.";
        exit();
    }

    // Hapus tugas dari database
    $query = "DELETE FROM tasks WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $task_id);
    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $db->error;
    }

    $db->close();
} else {
    header("Location: dashboard.php");
    exit();
}
?>
