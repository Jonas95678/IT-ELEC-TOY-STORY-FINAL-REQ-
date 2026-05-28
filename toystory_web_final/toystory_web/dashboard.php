<?php
session_start();
error_reporting(0);
ini_set('display_errors', '0');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
require_once "conn.php";

// Flash messages
$status     = '';
$statusType = 'success';
if (isset($_SESSION['status']))      { $status     = $_SESSION['status'];      unset($_SESSION['status']); }
if (isset($_SESSION['status_type'])) { $statusType = $_SESSION['status_type']; unset($_SESSION['status_type']); }

// Stat counts
$totalMovies   = (int) mysqli_num_rows(mysqli_query($conn, "SELECT id FROM `$tablemovies`"));
$totalChars    = (int) mysqli_num_rows(mysqli_query($conn, "SELECT id FROM `$tablechar`"));
$displayMovies = (int) mysqli_num_rows(mysqli_query($conn, "SELECT id FROM `$tablemovies` WHERE is_displayed=1"));
$displayChars  = (int) mysqli_num_rows(mysqli_query($conn, "SELECT id FROM `$tablechar`  WHERE is_displayed=1"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toy Story Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ── Status badges ─────────────────────────── */
        .badge-on  { display:inline-block; background:#22c55e; color:#fff; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
        .badge-off { display:inline-block; background:#ef4444; color:#fff; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }

        /* ── Toggle buttons ────────────────────────── */
        .btn-toggle-on  { background:rgba(239,68,68,.15)!important; border:1px solid #ef4444!important; color:#ef4444!important; border-radius:8px!important; padding:4px 10px!important; font-size:.78rem!important; }
        .btn-toggle-on:hover  { background:#ef4444!important; color:#fff!important; }
        .btn-toggle-off { background:rgba(34,197,94,.15)!important; border:1px solid #22c55e!important; color:#22c55e!important; border-radius:8px!important; padding:4px 10px!important; font-size:.78rem!important; }
        .btn-toggle-off:hover { background:#22c55e!important; color:#fff!important; }

        /* ── Glassmorphism inputs ───────────────────── */
        .glass-input {
            background: rgba(255,255,255,0.08) !important;
            border: 1px solid rgba(255,255,255,0.18) !important;
            color: #fff !important;
            border-radius: 10px !important;
        }
        .glass-input::placeholder { color: rgba(255,255,255,0.35) !important; }
        .glass-input:focus        { box-shadow: 0 0 0 2px rgba(244,197,66,.4) !important; outline: none !important; border-color: #F4C542 !important; }
        .glass-input option       { background: #1a2744; color: #fff; }
        textarea.glass-input      { resize: vertical; min-height: 80px; }

        /* ── Table fix: reset Bootstrap white background ── */
        .admin-table,
        .admin-table thead,
        .admin-table tbody,
        .admin-table tr,
        .admin-table th,
        .admin-table td {
            background-color: transparent !important;
            --bs-table-bg: transparent !important;
            --bs-table-striped-bg: transparent !important;
            --bs-table-hover-bg: rgba(255,255,255,0.04) !important;
            color: inherit !important;
            border-color: rgba(255,255,255,0.08) !important;
        }
        .admin-table th { color: #FFD700 !important; font-family:'Bangers',cursive; font-size:1rem; letter-spacing:1px; padding:1rem 1.2rem; }
        .admin-table td { padding:0.9rem 1.2rem; vertical-align:middle; color:rgba(255,249,230,0.92) !important; }
        .admin-table tbody tr:hover { background: rgba(255,255,255,0.04) !important; }

        /* ── Preview images ─────────────────────────── */
        .preview-img   { width:46px; height:58px; object-fit:cover; border-radius:6px; border:2px solid rgba(255,255,255,.15); }
        .avatar-preview img { width:42px; height:42px; object-fit:cover; border-radius:50%; border:2px solid rgba(255,255,255,.18); }

        /* ── Modal overlay ──────────────────────────── */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,.72);
            display: flex; align-items: center; justify-content: center;
            z-index: 9999; padding: 20px;
        }
        .modal-overlay.hidden { display: none !important; }

        /* ── Modal card ─────────────────────────────── */
        .admin-modal {
            max-width: 580px; width: 100%;
            padding: 2rem; border-radius: 20px;
            max-height: 90vh; overflow-y: auto;
            background: rgba(10,22,40,0.96) !important;
            border: 1px solid rgba(255,215,0,0.2);
            box-shadow: 0 25px 60px rgba(0,0,0,.6);
        }
        .admin-modal .modal-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255,215,0,0.15);
            padding-bottom: 1rem;
        }
        .admin-modal h2 { color: #F4C542; font-family:'Bangers',cursive; font-size:1.6rem; letter-spacing:1px; margin:0; }
        .modal-close { background:none; border:none; color:rgba(255,255,255,.5); font-size:1.2rem; cursor:pointer; transition:.2s; }
        .modal-close:hover { color:#ef4444; transform:scale(1.2); }
        .form-label { color:rgba(255,255,255,.8) !important; font-size:.82rem; font-weight:600; margin-bottom:.3rem; display:block; }
        .modal-actions { display:flex; gap:1rem; justify-content:flex-end; margin-top:1.5rem; padding-top:1rem; border-top:1px solid rgba(255,255,255,.08); }

        /* ── Action button row ──────────────────────── */
        .action-btn-group { display:flex; gap:5px; justify-content:center; flex-wrap:wrap; }
        .action-btn-group .btn { border-radius:8px !important; padding:4px 10px !important; font-size:.78rem !important; }

        /* ── Section top spacing ────────────────────── */
        .section-anchor { scroll-margin-top: 20px; }

        /* ── Fix Bootstrap alert over dark bg ───────── */
        .alert { border-radius: 12px !important; }
    </style>
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
        <div class="clouds-container night-clouds">
            <div class="cloud cloud-1"></div>
            <div class="cloud cloud-2"></div>
            <div class="cloud cloud-3"></div>
        </div>
        <div class="moon-glow"></div>
        <div class="lamp-glow"></div>
    </div>

    <div class="custom-cursor"  id="customCursor"></div>
    <div class="cursor-glow"    id="cursorGlow"></div>

    <!-- Admin Layout Wrapper -->
    <div class="admin-layout">

        <!-- ═══════════ SIDEBAR ═══════════ -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo"><i class="fas fa-hat-cowboy"></i></div>
                <span class="sidebar-title">TOY STORY</span>
                <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
            </div>
            <nav class="sidebar-nav">
                <ul class="nav-list">
                    <li class="nav-item active">
                        <a href="#dashboard" class="nav-link">
                            <i class="fas fa-tachometer-alt"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#movies-section" class="nav-link">
                            <i class="fas fa-film"></i>
                            <span class="nav-text">Movies</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#characters-section" class="nav-link">
                            <i class="fas fa-users"></i>
                            <span class="nav-text">Characters</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php" class="nav-link" target="_blank">
                            <i class="fas fa-globe"></i>
                            <span class="nav-text">View Website</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <a href="logout.php" class="nav-link logout-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="nav-text">Logout</span>
                </a>
            </div>
        </aside>

        <!-- ═══════════ MAIN AREA ═══════════ -->
        <main class="admin-main">

            <!-- Top Bar -->
            <header class="admin-topbar glassmorphism-card">
                <div class="topbar-left">
                    <button class="mobile-menu-toggle" id="mobileMenuToggle"><i class="fas fa-bars"></i></button>
                    <div class="admin-greeting">
                        <h1>Welcome back, <span class="highlight"><?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></span>!</h1>
                        <p>Manage your Toy Story fan site content</p>
                    </div>
                </div>
                <div class="topbar-right">
                    <a href="index.php" target="_blank"
                       class="btn btn-secondary cinematic-btn me-2"
                       style="font-size:.8rem;padding:.4rem 1rem;">
                        <i class="fas fa-external-link-alt me-1"></i> View Site
                    </a>
                    <div class="profile-avatar">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='50' fill='%23F4C542'/%3E%3Ctext x='50' y='62' font-size='48' text-anchor='middle' fill='%231a2744' font-family='Bangers'%3E<?php echo strtoupper(substr($_SESSION['username'],0,1)); ?>%3C/text%3E%3C/svg%3E"
                             alt="Avatar" class="avatar-img">
                    </div>
                </div>
            </header>

            <div class="admin-content" id="dashboard">

                <!-- Flash Message -->
                <?php if (!empty($status)): ?>
                <div class="alert alert-<?php echo ($statusType === 'error') ? 'danger' : 'success'; ?> alert-dismissible fade show mx-3 mt-3" role="alert">
                    <i class="fas fa-<?php echo ($statusType === 'error') ? 'exclamation-circle' : 'check-circle'; ?> me-2"></i>
                    <?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <!-- ── Stats ─────────────────────────────── -->
                <section class="stats-section">
                    <div class="stats-grid">
                        <div class="stat-card glassmorphism-card">
                            <div class="stat-icon movie-icon"><i class="fas fa-film"></i></div>
                            <div class="stat-info">
                                <h3 class="stat-number"><?php echo $totalMovies; ?></h3>
                                <p class="stat-label">Total Movies</p>
                            </div>
                            <div class="stat-glow"></div>
                        </div>
                        <div class="stat-card glassmorphism-card">
                            <div class="stat-icon character-icon"><i class="fas fa-users"></i></div>
                            <div class="stat-info">
                                <h3 class="stat-number"><?php echo $totalChars; ?></h3>
                                <p class="stat-label">Total Characters</p>
                            </div>
                            <div class="stat-glow"></div>
                        </div>
                        <div class="stat-card glassmorphism-card">
                            <div class="stat-icon update-icon"><i class="fas fa-eye"></i></div>
                            <div class="stat-info">
                                <h3 class="stat-number"><?php echo $displayMovies; ?></h3>
                                <p class="stat-label">Movies Displayed</p>
                            </div>
                            <div class="stat-glow"></div>
                        </div>
                        <div class="stat-card glassmorphism-card">
                            <div class="stat-icon session-icon"><i class="fas fa-star"></i></div>
                            <div class="stat-info">
                                <h3 class="stat-number"><?php echo $displayChars; ?></h3>
                                <p class="stat-label">Chars Displayed</p>
                            </div>
                            <div class="stat-glow"></div>
                        </div>
                    </div>
                </section>

                <!-- ══════════════════════════════════════════
                     MOVIES TABLE
                     ══════════════════════════════════════════ -->
                <section class="content-section section-anchor" id="movies-section">
                    <div class="section-header glassmorphism-card">
                        <div class="section-title-wrapper">
                            <h2><i class="fas fa-film"></i> Movies Management</h2>
                            <p>Add, edit, delete and control what shows on the website</p>
                        </div>
                        <button class="btn btn-primary cinematic-btn" id="addMovieBtn">
                            <i class="fas fa-plus"></i> <span>Add New Movie</span>
                            <span class="btn-shine"></span>
                        </button>
                    </div>

                    <div class="table-container glassmorphism-card">
                        <table class="admin-table w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Poster</th>
                                    <th>Title</th>
                                    <th>Year</th>
                                    <th>Runtime</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                    <th style="text-align:center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $mq = mysqli_query($conn,
                                "SELECT id, title, release_year, runtime, rating, poster_url, is_displayed
                                 FROM `$tablemovies`
                                 ORDER BY release_year ASC");
                            if ($mq && mysqli_num_rows($mq) > 0):
                                while ($m = mysqli_fetch_assoc($mq)):
                                    $mid    = (int)$m['id'];
                                    $mtitle = htmlspecialchars($m['title']      ?? '', ENT_QUOTES, 'UTF-8');
                                    $mpstr  = htmlspecialchars($m['poster_url'] ?? '', ENT_QUOTES, 'UTF-8');
                                    $mshown = (int)$m['is_displayed'];
                            ?>
                            <tr>
                                <td><?php echo $mid; ?></td>
                                <td>
                                    <img src="<?php echo $mpstr; ?>"
                                         class="preview-img"
                                         alt="<?php echo $mtitle; ?>"
                                         onerror="this.src='img/toystory1.webp'">
                                </td>
                                <td><strong><?php echo $mtitle; ?></strong></td>
                                <td><?php echo (int)$m['release_year']; ?></td>
                                <td><?php echo (int)$m['runtime']; ?> min</td>
                                <td><i class="fas fa-star" style="color:#F4C542;"></i> <?php echo number_format((float)($m['rating'] ?? 0), 1); ?></td>
                                <td>
                                    <?php if ($mshown): ?>
                                        <span class="badge-on"><i class="fas fa-eye me-1"></i>Shown</span>
                                    <?php else: ?>
                                        <span class="badge-off"><i class="fas fa-eye-slash me-1"></i>Hidden</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-btn-group">
                                        <a href="movie_toggle.php?id=<?php echo $mid; ?>"
                                           class="btn btn-sm <?php echo $mshown ? 'btn-toggle-on' : 'btn-toggle-off'; ?>"
                                           title="<?php echo $mshown ? 'Hide from site' : 'Show on site'; ?>">
                                            <i class="fas fa-<?php echo $mshown ? 'eye-slash' : 'eye'; ?>"></i>
                                            <?php echo $mshown ? 'Hide' : 'Show'; ?>
                                        </a>
                                        <a href="movie_edit.php?id=<?php echo $mid; ?>"
                                           class="btn btn-sm btn-warning" style="border-radius:8px;padding:4px 10px;font-size:.78rem;">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="movie_delete.php?id=<?php echo $mid; ?>"
                                           class="btn btn-sm btn-danger" style="border-radius:8px;padding:4px 10px;font-size:.78rem;"
                                           onclick="return confirm('Delete this movie permanently?');">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                                endwhile;
                            else:
                            ?>
                            <tr>
                                <td colspan="8" style="text-align:center;padding:2.5rem;color:rgba(255,255,255,.5);">
                                    <i class="fas fa-film me-2"></i> No movies yet — add one above!
                                </td>
                            </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- ══════════════════════════════════════════
                     CHARACTERS TABLE
                     ══════════════════════════════════════════ -->
                <section class="content-section section-anchor" id="characters-section">
                    <div class="section-header glassmorphism-card">
                        <div class="section-title-wrapper">
                            <h2><i class="fas fa-users"></i> Characters Management</h2>
                            <p>Add, edit, delete and control what shows on the website</p>
                        </div>
                        <button class="btn btn-primary cinematic-btn" id="addCharacterBtn">
                            <i class="fas fa-plus"></i> <span>Add New Character</span>
                            <span class="btn-shine"></span>
                        </button>
                    </div>

                    <div class="table-container glassmorphism-card">
                        <table class="admin-table w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Avatar</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Quote</th>
                                    <th>Status</th>
                                    <th style="text-align:center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $cq = mysqli_query($conn,
                                "SELECT id, `name`, role, quote, avatar_url, is_displayed
                                 FROM `$tablechar`
                                 ORDER BY id ASC");
                            if ($cq && mysqli_num_rows($cq) > 0):
                                while ($c = mysqli_fetch_assoc($cq)):
                                    $cid    = (int)$c['id'];
                                    $cname  = htmlspecialchars($c['name']       ?? 'Unknown', ENT_QUOTES, 'UTF-8');
                                    $crole  = htmlspecialchars($c['role']       ?? '',         ENT_QUOTES, 'UTF-8');
                                    $cquote = htmlspecialchars($c['quote']      ?? '',         ENT_QUOTES, 'UTF-8');
                                    $cavtr  = htmlspecialchars($c['avatar_url'] ?? 'img/woody.jpg', ENT_QUOTES, 'UTF-8');
                                    $cshown = (int)$c['is_displayed'];
                            ?>
                            <tr>
                                <td><?php echo $cid; ?></td>
                                <td>
                                    <img src="<?php echo $cavtr; ?>"
                                         alt="<?php echo $cname; ?>"
                                         style="width:42px;height:42px;object-fit:cover;border-radius:50%;border:2px solid rgba(255,255,255,.18);"
                                         onerror="this.src='img/woody.jpg'">
                                </td>
                                <td><strong><?php echo $cname; ?></strong></td>
                                <td><?php echo $crole; ?></td>
                                <td style="font-style:italic;color:rgba(255,249,230,.65);font-size:.82rem;">
                                    <?php echo mb_substr($cquote, 0, 40) . (mb_strlen($cquote) > 40 ? '…' : ''); ?>
                                </td>
                                <td>
                                    <?php if ($cshown): ?>
                                        <span class="badge-on"><i class="fas fa-eye me-1"></i>Shown</span>
                                    <?php else: ?>
                                        <span class="badge-off"><i class="fas fa-eye-slash me-1"></i>Hidden</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-btn-group">
                                        <a href="char_toggle.php?id=<?php echo $cid; ?>"
                                           class="btn btn-sm <?php echo $cshown ? 'btn-toggle-on' : 'btn-toggle-off'; ?>"
                                           title="<?php echo $cshown ? 'Hide from site' : 'Show on site'; ?>">
                                            <i class="fas fa-<?php echo $cshown ? 'eye-slash' : 'eye'; ?>"></i>
                                            <?php echo $cshown ? 'Hide' : 'Show'; ?>
                                        </a>
                                        <a href="char_edit.php?id=<?php echo $cid; ?>"
                                           class="btn btn-sm btn-warning" style="border-radius:8px;padding:4px 10px;font-size:.78rem;">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="char_delete.php?id=<?php echo $cid; ?>"
                                           class="btn btn-sm btn-danger" style="border-radius:8px;padding:4px 10px;font-size:.78rem;"
                                           onclick="return confirm('Delete this character permanently?');">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                                endwhile;
                            else:
                            ?>
                            <tr>
                                <td colspan="7" style="text-align:center;padding:2.5rem;color:rgba(255,255,255,.5);">
                                    <i class="fas fa-users me-2"></i> No characters yet — add one above!
                                </td>
                            </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

            </div><!-- /admin-content -->
        </main>
    </div><!-- /admin-layout -->


    <!-- ════════════════════════════════════════════
         MODAL — Add Movie
         ════════════════════════════════════════════ -->
    <div class="modal-overlay hidden" id="addMovieModal">
        <div class="admin-modal">
            <div class="modal-header">
                <h2><i class="fas fa-film me-2"></i>Add New Movie</h2>
                <button class="modal-close" id="closeMovieModal"><i class="fas fa-times"></i></button>
            </div>
            <form action="movie_add.php" method="POST">
                <div class="row g-3">
                    <div class="col-md-7">
                        <label class="form-label"><i class="fas fa-film me-1"></i> Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control glass-input" name="title" required placeholder="e.g. Toy Story 5">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label"><i class="fas fa-calendar me-1"></i> Release Year <span class="text-danger">*</span></label>
                        <input type="number" class="form-control glass-input" name="release_year" min="1990" max="2030" required placeholder="1995">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-clock me-1"></i> Runtime (min) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control glass-input" name="runtime" min="30" max="300" required placeholder="81">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-star me-1"></i> Rating (0–10)</label>
                        <input type="number" class="form-control glass-input" name="rating" min="0" max="10" step="0.1" placeholder="8.3">
                    </div>
                    <div class="col-12">
                        <label class="form-label"><i class="fas fa-quote-left me-1"></i> Tagline <span class="text-danger">*</span></label>
                        <textarea class="form-control glass-input" name="tagline" rows="2" required placeholder="Movie tagline…"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label"><i class="fas fa-image me-1"></i> Poster Image Path <span class="text-danger">*</span></label>
                        <input type="text" class="form-control glass-input" name="poster_url" required placeholder="img/toystory1.webp">
                        <small style="color:rgba(255,255,255,.35);font-size:.75rem;">Relative path, e.g. <code style="color:#F4C542;">img/toystory1.webp</code></small>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label"><i class="fas fa-eye me-1"></i> Display on Website?</label>
                        <select class="form-control glass-input" name="is_displayed">
                            <option value="1">Yes — Show</option>
                            <option value="0">No — Hide</option>
                        </select>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary cinematic-btn" id="cancelMovieBtn">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary cinematic-btn">
                        <i class="fas fa-save"></i> Save Movie <span class="btn-shine"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ════════════════════════════════════════════
         MODAL — Add Character
         ════════════════════════════════════════════ -->
    <div class="modal-overlay hidden" id="addCharacterModal">
        <div class="admin-modal">
            <div class="modal-header">
                <h2><i class="fas fa-user-plus me-2"></i>Add New Character</h2>
                <button class="modal-close" id="closeCharacterModal"><i class="fas fa-times"></i></button>
            </div>
            <form action="char_add.php" method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-user me-1"></i> Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control glass-input" name="char_name" required placeholder="e.g. Forky">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="fas fa-tag me-1"></i> Role <span class="text-danger">*</span></label>
                        <input type="text" class="form-control glass-input" name="role" required placeholder="e.g. The Spork">
                    </div>
                    <div class="col-12">
                        <label class="form-label"><i class="fas fa-quote-left me-1"></i> Famous Quote <span class="text-danger">*</span></label>
                        <textarea class="form-control glass-input" name="quote" rows="2" required placeholder='"I am not a toy!"'></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label"><i class="fas fa-align-left me-1"></i> Description <span class="text-danger">*</span></label>
                        <textarea class="form-control glass-input" name="description" rows="3" required placeholder="Character background and personality…"></textarea>
                    </div>
                    <div class="col-md-7">
                        <label class="form-label"><i class="fas fa-image me-1"></i> Avatar Image Path <span class="text-danger">*</span></label>
                        <input type="text" class="form-control glass-input" name="avatar_url" required placeholder="img/woody.jpg">
                        <small style="color:rgba(255,255,255,.35);font-size:.75rem;">Relative path, e.g. <code style="color:#F4C542;">img/woody.jpg</code></small>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label"><i class="fas fa-palette me-1"></i> Card Style Class</label>
                        <select class="form-control glass-input" name="css_class">
                            <option value="woody">woody (yellow)</option>
                            <option value="buzz">buzz (green)</option>
                            <option value="jessie">jessie (orange)</option>
                            <option value="rexy">rexy (lime)</option>
                            <option value="ham">ham (pink)</option>
                            <option value="slinky">slinky (blue)</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label"><i class="fas fa-eye me-1"></i> Display on Website?</label>
                        <select class="form-control glass-input" name="is_displayed">
                            <option value="1">Yes — Show</option>
                            <option value="0">No — Hide</option>
                        </select>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary cinematic-btn" id="cancelCharacterBtn">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary cinematic-btn">
                        <i class="fas fa-save"></i> Save Character <span class="btn-shine"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="admin-script.js"></script>
    <script>
    // ── Modal helpers ────────────────────────────────
    function openModal(id)  { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    document.getElementById('addMovieBtn')      .addEventListener('click', () => openModal('addMovieModal'));
    document.getElementById('closeMovieModal')  .addEventListener('click', () => closeModal('addMovieModal'));
    document.getElementById('cancelMovieBtn')   .addEventListener('click', () => closeModal('addMovieModal'));

    document.getElementById('addCharacterBtn')    .addEventListener('click', () => openModal('addCharacterModal'));
    document.getElementById('closeCharacterModal').addEventListener('click', () => closeModal('addCharacterModal'));
    document.getElementById('cancelCharacterBtn') .addEventListener('click', () => closeModal('addCharacterModal'));

    // Close on backdrop click
    ['addMovieModal','addCharacterModal'].forEach(id => {
        document.getElementById(id).addEventListener('click', e => {
            if (e.target.id === id) closeModal(id);
        });
    });

    // ── Sidebar toggle ───────────────────────────────
    const sidebar = document.getElementById('adminSidebar');
    const toggle  = document.getElementById('sidebarToggle');
    const mobile  = document.getElementById('mobileMenuToggle');
    if (toggle) toggle.addEventListener('click', () => sidebar.classList.toggle('collapsed'));
    if (mobile) mobile.addEventListener('click', () => sidebar.classList.toggle('mobile-open'));
    </script>
</body>
</html>
