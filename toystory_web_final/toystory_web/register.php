<?php
session_start();
$error   = '';
$success = '';

if (isset($_POST['register'])) {
    require_once "conn.php";

    $fname    = mysqli_real_escape_string($conn, trim($_POST['fname']));
    $mname    = mysqli_real_escape_string($conn, trim($_POST['mname']));
    $lname    = mysqli_real_escape_string($conn, trim($_POST['lname']));
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm_password']);

    if (empty($fname) || empty($lname) || empty($username) || empty($password) || empty($confirm)) {
        $error = "Please fill in all required fields.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $check = mysqli_query($conn, "SELECT * FROM $tablelogin WHERE username='$username'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Username already exists.";
        } else {
            $hashed = hash('sha256', $password);
            $sql = "INSERT INTO $tablelogin (fname, mname, lname, username, password)
                    VALUES ('$fname','$mname','$lname','$username','$hashed')";
            if (mysqli_query($conn, $sql)) {
                $success = "Account created! You can now <a href='login.php'>login</a>.";
            } else {
                $error = "Registration failed: " . mysqli_error($conn);
            }
        }
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register | Toy Story Fan Site</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="admin-dashboard-body">

    <div class="background-container cinematic-bg admin-bg">
        <div class="sky-background night-sky"></div>
        <div class="stars-container">
            <div class="star-layer star-layer-1"></div>
            <div class="star-layer star-layer-2"></div>
        </div>
        <div class="moon-glow"></div>
    </div>

    <div class="d-flex justify-content-center align-items-center min-vh-100 py-5" style="position:relative;z-index:10;">
        <div class="glassmorphism-card p-5 shadow" style="min-width:420px; max-width:500px; width:100%; border-radius:20px;">

            <div class="text-center mb-4">
                <i class="fas fa-user-plus" style="font-size:2rem; color:#F4C542;"></i>
                <h2 class="mt-2" style="font-family:'Bangers',cursive; letter-spacing:2px; color:#F4C542; font-size:2rem;">Create Account</h2>
                <p style="color:rgba(255,255,255,0.7); font-size:.85rem;">Register as Admin</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" name="formReg">
                <div class="row g-2 mb-3">
                    <div class="col-5">
                        <label class="form-label" style="color:rgba(255,255,255,.8);">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control glass-input" name="fname" required>
                    </div>
                    <div class="col-3">
                        <label class="form-label" style="color:rgba(255,255,255,.8);">Middle</label>
                        <input type="text" class="form-control glass-input" name="mname">
                    </div>
                    <div class="col-4">
                        <label class="form-label" style="color:rgba(255,255,255,.8);">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control glass-input" name="lname" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" style="color:rgba(255,255,255,.8);"><i class="fas fa-user me-1"></i> Username <span class="text-danger">*</span></label>
                    <input type="text" class="form-control glass-input" name="username" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" style="color:rgba(255,255,255,.8);"><i class="fas fa-lock me-1"></i> Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control glass-input" name="password" required>
                </div>
                <div class="mb-4">
                    <label class="form-label" style="color:rgba(255,255,255,.8);"><i class="fas fa-lock me-1"></i> Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control glass-input" name="confirm_password" required>
                </div>
                <button type="submit" name="register" class="btn btn-primary cinematic-btn w-100 mb-3">
                    <i class="fas fa-user-plus me-2"></i> Register
                    <span class="btn-shine"></span>
                </button>
                <div class="text-center" style="color:rgba(255,255,255,.6); font-size:.85rem;">
                    Already have an account? <a href="login.php" style="color:#F4C542;">Login here</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
