<?php
session_start();
error_reporting(0);
ini_set('display_errors','0');
if (!isset($_SESSION['username'])) { header("Location: login.php"); exit(); }
require_once "conn.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) { header("Location: dashboard.php"); exit(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title        = mysqli_real_escape_string($conn, trim($_POST['title']        ?? ''));
    $release_year = (int)($_POST['release_year'] ?? 1995);
    $tagline      = mysqli_real_escape_string($conn, trim($_POST['tagline']      ?? ''));
    $runtime      = (int)($_POST['runtime']      ?? 90);
    $rating       = isset($_POST['rating']) && $_POST['rating'] !== '' ? (float)$_POST['rating'] : 0.0;
    $poster_url   = mysqli_real_escape_string($conn, trim($_POST['poster_url']   ?? 'img/toystory1.webp'));
    $is_displayed = (int)($_POST['is_displayed']  ?? 1);

    $sql = "UPDATE `$tablemovies` SET
                title='$title', release_year=$release_year, tagline='$tagline',
                runtime=$runtime, rating=$rating,
                poster_url='$poster_url', is_displayed=$is_displayed
            WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['status'] = "Movie \"$title\" updated successfully!";
    } else {
        $_SESSION['status']      = "Error updating movie: " . mysqli_error($conn);
        $_SESSION['status_type'] = 'error';
    }
    header("Location: dashboard.php#movies-section");
    exit();
}

$q   = mysqli_query($conn, "SELECT * FROM `$tablemovies` WHERE id=$id");
$row = $q ? mysqli_fetch_assoc($q) : null;
if (!$row) { header("Location: dashboard.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Movie | Toy Story Admin</title>
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
                <a href="dashboard.php#movies-section"
                   class="btn btn-secondary cinematic-btn"
                   style="padding:.4rem .9rem;font-size:.85rem;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="page-title">
                    <i class="fas fa-film me-2"></i>Edit Movie
                </h2>
            </div>

            <form action="" method="POST">
                <div class="row g-3">
                    <div class="col-md-7">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control glass-input"
                               name="title" required
                               value="<?php echo htmlspecialchars($row['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Release Year <span class="text-danger">*</span></label>
                        <input type="number" class="form-control glass-input"
                               name="release_year" min="1990" max="2030" required
                               value="<?php echo (int)($row['release_year'] ?? 1995); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Runtime (min) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control glass-input"
                               name="runtime" min="30" max="300" required
                               value="<?php echo (int)($row['runtime'] ?? 90); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Rating (0–10)</label>
                        <input type="number" class="form-control glass-input"
                               name="rating" min="0" max="10" step="0.1"
                               value="<?php echo number_format((float)($row['rating'] ?? 0), 1); ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Tagline <span class="text-danger">*</span></label>
                        <textarea class="form-control glass-input" name="tagline" rows="2" required><?php echo htmlspecialchars($row['tagline'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>
                    <div class="col-md-7">
                        <label class="form-label">Poster Image Path <span class="text-danger">*</span></label>
                        <input type="text" class="form-control glass-input"
                               name="poster_url" required
                               value="<?php echo htmlspecialchars($row['poster_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        <small>e.g. <code>img/toystory1.webp</code></small>
                    </div>
                    <div class="col-md-5 d-flex flex-column">
                        <label class="form-label">Current Poster</label>
                        <img src="<?php echo htmlspecialchars($row['poster_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                             alt="poster"
                             style="height:80px;border-radius:8px;border:2px solid rgba(255,215,0,.35);"
                             onerror="this.src='img/toystory1.webp'">
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
                    <a href="dashboard.php#movies-section"
                       class="btn btn-secondary cinematic-btn">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary cinematic-btn">
                        <i class="fas fa-save"></i> Update Movie
                        <span class="btn-shine"></span>
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
