<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['task_id'])) {
    header("Location: dashboard.php");
    exit();
}

$task_id = $_POST['task_id'];
$title = $_POST['title'];
$description = $_POST['description'];
$status = $_POST['status'];
$priority = $_POST['priority'];
$start_date = $_POST['start_date'];
$due_date = $_POST['due_date'];
$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("UPDATE tasks SET 
        title = ?, 
        description = ?, 
        status = ?, 
        priority = ?, 
        start_date = ?, 
        due_date = ? 
        WHERE id = ? AND user_id = ?");
        
    $stmt->execute([$title, $description, $status, $priority, $start_date, $due_date, $task_id, $user_id]);
    
    header("Location: dashboard.php?success=Task updated successfully");
} catch(PDOException $e) {
    header("Location: dashboard.php?error=Failed to update task");
}
?> 