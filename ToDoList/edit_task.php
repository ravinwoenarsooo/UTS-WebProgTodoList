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

    $row = $result->fetch_assoc();

    // Tampilkan formulir untuk mengedit tugas
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Edit Tugas</title>
    </head>
    <body>
        <h2>Edit Tugas</h2>
        <form action="edit_task.php" method="post">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <label for="title">Judul:</label>
            <input type="text" name="title" value="<?php echo $row['title']; ?>" required><br>

            <label for="description">Deskripsi:</label>
            <textarea name="description"><?php echo $row['description']; ?></textarea><br>

            <label for="due_date">Tanggal Jatuh Tempo:</label>
            <input type="date" name="due_date" value="<?php echo $row['due_date']; ?>" required><br>

            <label for="status">Status:</label>
            <select name="status">
                <option value="Not started" <?php if ($row['status'] == 'Not started') echo 'selected'; ?>>Not started</option>
                <option value="In progress" <?php if ($row['status'] == 'In progress') echo 'selected'; ?>>In progress</option>
                <option value="Waiting on" <?php if ($row['status'] == 'Waiting on') echo 'selected'; ?>>Waiting on</option>
                <option value="Done" <?php if ($row['status'] == 'Done') echo 'selected'; ?>>Done</option>
            </select><br>

            <input type="submit" value="Simpan Perubahan">
        </form>
    </body>
    </html>
    <?php
    $db->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    // Koneksi ke database (ganti dengan informasi koneksi Anda)
    $db = new mysqli('localhost', 'username', 'password', 'nama_database');

    if ($db->connect_error) {
        die("Koneksi database gagal: " . $db->connect_error);
    }

    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    // Perbarui tugas dalam database
    $query = "UPDATE tasks SET title = ?, description = ?, due_date = ?, status = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ssssi", $title, $description, $due_date, $status, $id);
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
