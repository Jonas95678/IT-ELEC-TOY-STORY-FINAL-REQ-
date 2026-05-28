<?php
session_start();
$error = '';

if (isset($_POST['login'])) {
    require_once "conn.php";

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = hash('sha256', $_POST['password']);

    $sql    = "SELECT * FROM $tablelogin WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Invalid username and/or password.";
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Toy Story Fan Site</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="admin-dashboard-body">

    <!-- Animated Background -->
    <div class="background-container cinematic-bg admin-bg">
        <div class="sky-background night-sky"></div>
        <div class="stars-container">
            <div class="star-layer star-layer-1"></div>
            <div class="star-layer star-layer-2"></div>
            <div class="star-layer star-layer-3"></div>
        </div>
        <div class="moon-glow"></div>
        <div class="lamp-glow"></div>
    </div>

    <div class="d-flex justify-content-center align-items-center vh-100" style="position:relative;z-index:10;">
        <div class="glassmorphism-card p-5 shadow" style="min-width:380px; max-width:420px; width:100%; border-radius:20px;">

            <div class="text-center mb-4">
                <i class="fas fa-hat-cowboy" style="font-size:2.5rem; color:var(--accent-yellow, #F4C542);"></i>
                <h2 class="mt-2" style="font-family:'Bangers',cursive; letter-spacing:2px; color:#F4C542; font-size:2rem;">TOY STORY</h2>
                <p style="color:rgba(255,255,255,0.7); font-size:.85rem;">Admin Panel — Sign In</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form name="frmLogin" action="" method="POST">
                <div class="mb-3">
                    <label class="form-label" style="color:rgba(255,255,255,.8);">
                        <i class="fas fa-user me-1"></i> Username
                    </label>
                    <input type="text" class="form-control glass-input" name="username" required
                           placeholder="Enter your username">
                </div>
                <div class="mb-4">
                    <label class="form-label" style="color:rgba(255,255,255,.8);">
                        <i class="fas fa-lock me-1"></i> Password
                    </label>
                    <input type="password" class="form-control glass-input" name="password" required
                           placeholder="Enter your password">
                </div>
                <button type="submit" name="login" class="btn btn-primary cinematic-btn w-100 mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i> Login
                    <span class="btn-shine"></span>
                </button>
                <div class="text-center" style="color:rgba(255,255,255,.6); font-size:.85rem;">
                    No account? <a href="register.php" style="color:#F4C542;">Register here</a>
                </div>
            </form>

            <div class="text-center mt-3">
                <a href="index.php" style="color:rgba(255,255,255,.5); font-size:.8rem;">
                    <i class="fas fa-arrow-left me-1"></i> Back to Website
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
