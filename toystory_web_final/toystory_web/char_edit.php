<?php
session_start();
error_reporting(0);
ini_set('display_errors','0');
if (!isset($_SESSION['username'])) { header("Location: login.php"); exit(); }
require_once "conn.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) { header("Location: dashboard.php"); exit(); }

// Handle POST update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name         = mysqli_real_escape_string($conn, trim($_POST['char_name']   ?? ''));
    $role         = mysqli_real_escape_string($conn, trim($_POST['role']        ?? ''));
    $quote        = mysqli_real_escape_string($conn, trim($_POST['quote']       ?? ''));
    $description  = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
    $avatar_url   = mysqli_real_escape_string($conn, trim($_POST['avatar_url']  ?? 'img/woody.jpg'));
    $css_class    = mysqli_real_escape_string($conn, trim($_POST['css_class']   ?? 'woody'));
    $is_displayed = (int)($_POST['is_displayed'] ?? 1);

    $sql = "UPDATE `$tablechar` SET
                `name`='$name', role='$role', quote='$quote',
                description='$description', avatar_url='$avatar_url',
                css_class='$css_class', is_displayed=$is_displayed
            WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['status'] = "Character \"$name\" updated successfully!";
    } else {
        $_SESSION['status']      = "Error updating character: " . mysqli_error($conn);
        $_SESSION['status_type'] = 'error';
    }
    header("Location: dashboard.php#characters-section");
    exit();
}

// Fetch for the edit form
$q   = mysqli_query($conn, "SELECT * FROM `$tablechar` WHERE id=$id");
$row = $q ? mysqli_fetch_assoc($q) : null;
if (!$row) { header("Location: dashboard.php"); exit(); }

$cssOptions = ['woody','buzz','jessie','rexy','ham','slinky'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Character | Toy Story Admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height:100vh; }
        .glass-input {
            background: rgba(255,255,255,0.08) !important;
            border: 1px solid rgba(255,255,255,0.18) !important;
            color: #fff !important;
            border-radius: 10px !important;
        }
        .glass-input::placeholder { color: rgba(255,255,255,.35) !important; }
        .glass-input:focus { box-shadow: 0 0 0 2px rgba(244,197,66,.4) !important; outline:none !important; border-color:#F4C542 !important; }
        .glass-input option { background:#1a2744; color:#fff; }
        textarea.glass-input { resize:vertical; min-height:80px; }
        .form-label { color:rgba(255,255,255,.82) !important; font-size:.82rem; font-weight:600; }
        .edit-wrap  { max-width:640px; margin:0 auto; padding:2.5rem; border-radius:20px; }
        .page-title { font-family:'Bangers',cursive; color:#F4C542; font-size:1.8rem; letter-spacing:1px; margin:0; }
        small code  { color:#F4C542; background:rgba(244,197,66,.1); padding:1px 5px; border-radius:4px; }
    </style>
</head>
<body class="admin-dashboard-body">

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

    <div class="container py-5" style="position:relative;z-index:10;">
        <div class="glassmorphism-card edit-wrap">

            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="dashboard.php#characters-section"
                   class="btn btn-secondary cinematic-btn"
                   style="padding:.4rem .9rem;font-size:.85rem;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="page-title">
                    <i class="fas fa-user-edit me-2"></i>Edit Character
                </h2>
            </div>

            <form action="" method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control glass-input"
                               name="char_name" required
                               value="<?php echo htmlspecialchars($row['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <input type="text" class="form-control glass-input"
                               name="role" required
                               value="<?php echo htmlspecialchars($row['role'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Famous Quote <span class="text-danger">*</span></label>
                        <textarea class="form-control glass-input" name="quote" rows="2" required><?php echo htmlspecialchars($row['quote'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control glass-input" name="description" rows="3" required><?php echo htmlspecialchars($row['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>
                    <div class="col-md-7">
                        <label class="form-label">Avatar Image Path <span class="text-danger">*</span></label>
                        <input type="text" class="form-control glass-input"
                               name="avatar_url" required
                               value="<?php echo htmlspecialchars($row['avatar_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        <small>e.g. <code>img/woody.jpg</code></small>
                    </div>
                    <div class="col-md-5 d-flex flex-column">
                        <label class="form-label">Current Avatar</label>
                        <img src="<?php echo htmlspecialchars($row['avatar_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                             alt="avatar"
                             style="width:60px;height:60px;object-fit:cover;border-radius:50%;border:2px solid rgba(255,215,0,.35);"
                             onerror="this.src='img/woody.jpg'">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Card Style Class</label>
                        <select class="form-control glass-input" name="css_class">
                            <?php foreach ($cssOptions as $cls): ?>
                            <option value="<?php echo $cls; ?>"
                                <?php echo ($row['css_class'] ?? '') === $cls ? 'selected' : ''; ?>>
                                <?php echo $cls; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Display on Website?</label>
                        <select class="form-control glass-input" name="is_displayed">
                            <option value="1" <?php echo ($row['is_displayed'] ?? 1) ? 'selected' : ''; ?>>Yes — Show</option>
                            <option value="0" <?php echo !($row['is_displayed'] ?? 1) ? 'selected' : ''; ?>>No — Hide</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-3 mt-4 justify-content-end">
                    <a href="dashboard.php#characters-section"
                       class="btn btn-secondary cinematic-btn">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary cinematic-btn">
                        <i class="fas fa-save"></i> Update Character
                        <span class="btn-shine"></span>
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
