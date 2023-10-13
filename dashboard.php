<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Koneksi ke database (ganti dengan informasi koneksi Anda)
$db = new mysqli('localhost', 'username', 'password', 'nama_database');

if ($db->connect_error) {
    die("Koneksi database gagal: " . $db->connect_error);
}

// Ambil daftar tugas dari database untuk pengguna yang sedang login
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM tasks WHERE user_id = $user_id";
$result = $db->query($query);

if ($result === false) {
    die("Error: " . $query . "<br>" . $db->error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Dashboard</h2>
    <a href="add_task.php">Tambah Tugas Baru</a>
    <table>
        <tr>
            <th>Judul</th>
            <th>Deskripsi</th>
            <th>Tanggal Jatuh Tempo</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['title'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td>" . $row['due_date'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td><a href='edit_task.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete_task.php?id=" . $row['id'] . "'>Hapus</a></td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>
<?php
// Tutup koneksi database
$db->close();
?>
