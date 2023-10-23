<?php
require '../db_conn.php';
session_start();

if (!isset($_SESSION['user_authenticated']) || !$_SESSION['user_authenticated']) {
    header("Location: login_form.php");
    exit();
}

if (isset($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];

    if (!empty($edit_id)) {
        // Fetch the existing title for editing
        $stmt = $conn->prepare("SELECT title FROM todos WHERE id = ?");
        $stmt->execute([$edit_id]);
        $todo = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

if (isset($_POST['update_todo'])) {
    $update_id = $_POST['edit_id'];
    $new_title = $_POST['new_title'];

    if (!empty($new_title)) {
        $stmt = $conn->prepare("UPDATE todos SET title = ? WHERE id = ?");
        $stmt->execute([$new_title, $update_id]);

        // Return a success message to indicate a successful update
        echo 'success';
        exit();
    }
}

// Add any additional code if needed
?>
