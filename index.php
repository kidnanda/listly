<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Listly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e6f0ff;
        }
        .hero-section {
            padding: 100px 0;
            text-align: center;
        }
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            height: 100%;
            transition: transform 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .logo {
            width: 150px;
            margin-bottom: 30px;
        }
        .btn-custom {
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 500;
            margin: 0 10px;
        }
        .btn-get-started {
            background-color: #1a73e8;
            color: white;
        }
        .btn-get-started:hover {
            background-color: #1557b0;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="assets/logo.png" alt="Listly" height="30">
            </a>
            <div class="ms-auto">
                <a href="login.php" class="btn btn-outline-primary btn-custom">Sign In</a>
                <a href="register.php" class="btn btn-get-started btn-custom">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <img src="assets/logo.png" alt="Listly Logo" class="logo">
            <h1 class="display-4 mb-4">Organize Your Tasks, Simplify Your Life</h1>
            <p class="lead text-muted mb-5">
                Stay organized and boost your productivity with Listly. The simple and effective way to manage your daily tasks.
            </p>
            <div class="mb-5">
                <a href="register.php" class="btn btn-get-started btn-custom btn-lg">Get Started</a>
                <a href="login.php" class="btn btn-outline-primary btn-custom btn-lg">Sign In</a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="container mb-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <h3>Easy Task Management</h3>
                    <p class="text-muted">Create, organize, and track your tasks with just a few clicks</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <h3>Progress Tracking</h3>
                    <p class="text-muted">Monitor your productivity with visual progress indicators</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <h3>Calendar Integration</h3>
                    <p class="text-muted">Schedule and view your tasks in an intuitive calendar view</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white py-4">
        <div class="container text-center">
            <p class="text-muted mb-0">© 2024 Listly. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 