<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Koneksi ke database (ganti dengan informasi koneksi Anda)
    $db = new mysqli('localhost', 'username', 'password', 'nama_database');

    if ($db->connect_error) {
        die("Koneksi database gagal: " . $db->connect_error);
    }

    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $status = 'Not started'; // Status awal

    // Insert tugas ke database
    $query = "INSERT INTO tasks (user_id, title, description, due_date, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("issss", $user_id, $title, $description, $due_date, $status);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $db->error;
    }

    // Tutup koneksi database
    $db->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Tugas Baru</title>
</head>
<body>
    <h2>Tambah Tugas Baru</h2>
    <form action="add_task.php" method="post">
        <label for="title">Judul:</label>
        <input type="text" name="title" required><br>

        <label for="description">Deskripsi:</label>
        <textarea name="description"></textarea><br>

        <label for="due_date">Tanggal Jatuh Tempo:</label>
        <input type="date" name="due_date" required><br>

        <input type="submit" value="Tambah Tugas">
    </form>
</body>
</html>
