<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $start_date = $_POST['start_date'];
    $due_date = $_POST['due_date'];
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, priority, start_date, due_date, status) 
                              VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
        $stmt->execute([$user_id, $title, $description, $priority, $start_date, $due_date]);

        header("Location: dashboard.php?success=Task added successfully");
        exit();
    } catch(PDOException $e) {
        header("Location: dashboard.php?error=Failed to add task");
        exit();
    }
}

header("Location: dashboard.php");
exit();
?> 