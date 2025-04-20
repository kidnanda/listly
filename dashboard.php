<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Fetch tasks
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY due_date ASC");
$stmt->execute([$_SESSION['user_id']]);
$tasks = $stmt->fetchAll();

// Calculate progress
$total_tasks = count($tasks);
$completed_tasks = 0;
foreach ($tasks as $task) {
    if ($task['status'] == 'Completed') {
        $completed_tasks++;
    }
}
$progress = $total_tasks > 0 ? ($completed_tasks / $total_tasks) * 100 : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Listly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: white;
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px;
            border-right: 1px solid #e9ecef;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
            color: #1a73e8;
            text-decoration: none;
        }
        .logo img {
            height: 30px;
        }
        .menu-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            color: #666;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 5px;
        }
        .menu-item:hover, .menu-item.active {
            background-color: #f0f7ff;
            color: #1a73e8;
        }
        .search-bar {
            width: 100%;
            max-width: 400px;
            padding: 10px 20px;
            border: 1px solid #e9ecef;
            border-radius: 25px;
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .progress {
            height: 10px;
            border-radius: 5px;
        }
        .progress-bar {
            background-color: #1a73e8;
        }
        .user-menu {
            position: relative;
        }
        .dropdown-menu {
            right: 0;
            left: auto;
        }
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            .main-content {
                margin-left: 0;
            }
        }
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            position: relative;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#" class="logo">
            <img src="assets/logo.png" alt="Listly">
        </a>
        <div class="mb-4">Menu</div>
        <a href="#" class="menu-item active">
            <i class="bi bi-house"></i>
            Dashboard
        </a>
        <a href="#" class="menu-item">
            <i class="bi bi-calendar"></i>
            Calendar
        </a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header-container">
            <h4>Dashboard</h4>
            <input type="search" class="search-bar" placeholder="Search tasks...">
            <div class="dropdown user-menu">
                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php echo htmlspecialchars($user['username']); ?>
                </button>
                <ul class="dropdown-menu shadow">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="logout.php">Log out</a></li>
                </ul>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <h5 class="mb-4">Task Progress</h5>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" 
                             style="width: <?php echo $progress; ?>%"
                             aria-valuenow="<?php echo $progress; ?>" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
                    </div>
                    <div class="mt-2 text-muted">
                        <?php echo round($progress); ?>% of tasks completed
                    </div>
                </div>

                <div class="card">
                    <h5>Calendar</h5>
                    <div id="calendar"></div>
                </div>

                <div class="card mt-4">
                    <h5>Tasks</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>JUDUL</th>
                                    <th>DESKRIPSI</th>
                                    <th>STATUS</th>
                                    <th>PRIORITAS</th>
                                    <th>TANGGAL MULAI</th>
                                    <th>TANGGAL SELESAI</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                foreach ($tasks as $task): 
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($task['title']); ?></td>
                                    <td><?php echo htmlspecialchars($task['description']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $task['status'] == 'Completed' ? 'success' : 
                                                ($task['status'] == 'Progress' ? 'primary' : 'warning'); 
                                        ?>">
                                            <?php echo $task['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $task['priority'] == 'High' ? 'danger' : 
                                                ($task['priority'] == 'Medium' ? 'warning' : 'info'); 
                                        ?>">
                                            <?php echo $task['priority']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d M Y', strtotime($task['start_date'])); ?></td>
                                    <td><?php echo date('d M Y', strtotime($task['due_date'])); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editTask<?php echo $task['id']; ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteTask(<?php echo $task['id']; ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editTask<?php echo $task['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Task</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="edit_task.php" method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                                    <div class="mb-3">
                                                        <label class="form-label">Title</label>
                                                        <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Description</label>
                                                        <textarea class="form-control" name="description"><?php echo htmlspecialchars($task['description']); ?></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Status</label>
                                                        <select class="form-control" name="status">
                                                            <option value="Pending" <?php echo $task['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                            <option value="Progress" <?php echo $task['status'] == 'Progress' ? 'selected' : ''; ?>>Progress</option>
                                                            <option value="Completed" <?php echo $task['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Priority</label>
                                                        <select class="form-control" name="priority">
                                                            <option value="Low" <?php echo $task['priority'] == 'Low' ? 'selected' : ''; ?>>Low</option>
                                                            <option value="Medium" <?php echo $task['priority'] == 'Medium' ? 'selected' : ''; ?>>Medium</option>
                                                            <option value="High" <?php echo $task['priority'] == 'High' ? 'selected' : ''; ?>>High</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Start Date</label>
                                                        <input type="date" class="form-control" name="start_date" value="<?php echo $task['start_date']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Due Date</label>
                                                        <input type="date" class="form-control" name="due_date" value="<?php echo $task['due_date']; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <h5 class="mb-4">Quick Add Task</h5>
                    <form action="add_task.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" placeholder="Task title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" placeholder="Task description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Priority</label>
                            <select class="form-control" name="priority" required>
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" class="form-control" name="due_date" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add Task</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    <?php foreach ($tasks as $task): ?>
                    {
                        title: '<?php echo addslashes($task['title']); ?>',
                        start: '<?php echo $task['due_date']; ?>',
                        backgroundColor: '<?php 
                            echo $task['priority'] == 'High' ? '#dc3545' : 
                                ($task['priority'] == 'Medium' ? '#ffc107' : '#0dcaf0'); 
                        ?>'
                    },
                    <?php endforeach; ?>
                ]
            });
            calendar.render();
        });

        function deleteTask(taskId) {
            if (confirm('Are you sure you want to delete this task?')) {
                window.location.href = 'delete_task.php?id=' + taskId;
            }
        }
    </script>
</body>
</html>
