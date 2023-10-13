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

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $status = $_POST['status'];

    // Ambil daftar tugas berdasarkan status yang dipilih
    $query = "SELECT * FROM tasks WHERE user_id = ? AND status = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("is", $user_id, $status);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Tampilkan semua tugas jika tidak ada filter status
    $query = "SELECT * FROM tasks WHERE user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Progress</title>
</head>
<body>
    <h2>Progress</h2>
    <form action="progress.php" method="post">
        <label for="status">Filter berdasarkan Status:</label>
        <select name="status">
            <option value="Not started">Not started</option>
            <option value="In progress">In progress</option>
            <option value="Waiting on">Waiting on</option>
            <option value="Done">Done</option>
        </select>
        <input type="submit" value="Filter">
    </form>
    <table>
        <tr>
            <th>Judul</th>
            <th>Deskripsi</th>
            <th>Tanggal Jatuh Tempo</th>
            <th>Status</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['title'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td>" . $row['due_date'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
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
