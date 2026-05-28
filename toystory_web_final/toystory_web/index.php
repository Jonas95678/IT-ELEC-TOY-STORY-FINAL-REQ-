<?php
// ── Must be absolute first lines ──────────────────────────────
session_start();
error_reporting(0);
ini_set('display_errors', '0');

require_once "conn.php";

$isAdmin = isset($_SESSION['username']);

// Fetch displayed movies
$movies     = [];
$mov_result = mysqli_query($conn,
    "SELECT id, title, release_year, tagline, runtime, rating, poster_url
     FROM `$tablemovies`
     WHERE is_displayed = 1
     ORDER BY release_year ASC");
if ($mov_result) {
    while ($row = mysqli_fetch_assoc($mov_result)) {
        $movies[] = $row;
    }
}

// Fetch displayed characters
$characters  = [];
$char_result = mysqli_query($conn,
    "SELECT id, `name`, role, quote, description, avatar_url, css_class
     FROM `$tablechar`
     WHERE is_displayed = 1
     ORDER BY id ASC");
if ($char_result) {
    while ($row = mysqli_fetch_assoc($char_result)) {
        $characters[] = $row;
    }
}

// Static timeline (About section)
$timeline = [
    ['year' => 1995, 'event' => 'Toy Story 1', 'detail' => 'The original classic'],
    ['year' => 1999, 'event' => 'Toy Story 2', 'detail' => 'The toys are back!'],
    ['year' => 2010, 'event' => 'Toy Story 3', 'detail' => 'No toy gets left behind'],
    ['year' => 2019, 'event' => 'Toy Story 4', 'detail' => 'Find your inner voice'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toy Story Fan Site | To Infinity and Beyond</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Cinematic Loading Screen -->
    <div class="cinematic-loader" id="cinematicLoader">
        <div class="loader-content">
            <div class="loader-logo">
                <i class="fas fa-hat-cowboy"></i>
                <span>TOY STORY</span>
            </div>
            <div class="loader-bar">
                <div class="loader-progress"></div>
            </div>
            <p class="loader-text">Loading Andy's Room...</p>
        </div>
    </div>

    <!-- Custom Cursor -->
    <div class="custom-cursor" id="customCursor"></div>
    <div class="cursor-glow" id="cursorGlow"></div>

    <!-- Animated Background -->
    <div class="background-container cinematic-bg">
        <div class="sky-background night-sky"></div>
        <div class="stars-container">
            <div class="star-layer star-layer-1"></div>
            <div class="star-layer star-layer-2"></div>
            <div class="star-layer star-layer-3"></div>
        </div>
        <div class="clouds-container night-clouds">
            <div class="cloud cloud-1"></div><div class="cloud cloud-2"></div>
            <div class="cloud cloud-3"></div><div class="cloud cloud-4"></div>
            <div class="cloud cloud-5"></div>
        </div>
        <div class="moon-glow"></div>
        <div class="lamp-glow"></div>
        <div class="floating-shapes cinematic-shapes">
            <div class="shape star"><i class="fas fa-star"></i></div>
            <div class="shape block"><i class="fas fa-cube"></i></div>
            <div class="shape rocket"><i class="fas fa-rocket"></i></div>
            <div class="shape ball"><i class="fas fa-circle"></i></div>
            <div class="shape dinosaur"><i class="fas fa-dragon"></i></div>
            <div class="shape sheriff"><i class="fas fa-star"></i></div>
        </div>
        <div class="fog-layer"></div>
        <div class="toy-silhouettes">
            <div class="silhouette silhouette-1"><i class="fas fa-cube"></i></div>
            <div class="silhouette silhouette-2"><i class="fas fa-shapes"></i></div>
            <div class="silhouette silhouette-3"><i class="fas fa-puzzle-piece"></i></div>
        </div>
    </div>

    <!-- ═══ NAVIGATION ═══ -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <span class="logo-text">TOY STORY</span>
                <span class="logo-subtitle">Fan Experience</span>
            </div>
            <ul class="nav-menu">
                <li><a href="#home"       class="nav-link active">Home</a></li>
                <li><a href="#about"      class="nav-link">About</a></li>
                <li><a href="#movies"     class="nav-link">Movies</a></li>
                <li><a href="#characters" class="nav-link">Characters</a></li>
                <li><a href="#contact"    class="nav-link">Contact</a></li>
            </ul>
            <div class="nav-actions" id="navActions">
                <?php if ($isAdmin): ?>
                    <a href="dashboard.php" class="btn btn-login" style="margin-right:.5rem;">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="logout.php" class="btn btn-login">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                <?php else: ?>
                    <a href="register.php" class="btn btn-login" style="margin-right:.5rem;">
                        <i class="fas fa-user-plus"></i>
                        <span>Register</span>
                    </a>
                    <a href="login.php" class="btn btn-login">
                        <i class="fas fa-user-shield"></i>
                        <span>Admin Login</span>
                    </a>
                <?php endif; ?>
            </div>
            <div class="hamburger">
                <span></span><span></span><span></span>
            </div>
        </div>
    </nav>

    <!-- ════════════════ HERO ════════════════ -->
    <section id="home" class="hero-section cinematic-hero">
        <div class="hero-parallax-layer hero-bg-layer"></div>
        <div class="hero-parallax-layer hero-mid-layer"></div>
        <div class="hero-parallax-layer hero-fg-layer"></div>

        <div class="hero-content cinematic-hero-content">
            <div class="hero-badge cinematic-badge">
                <i class="fas fa-film"></i> A Pixar Masterpiece
                <span class="badge-glow"></span>
            </div>
            <h1 class="hero-title cinematic-title">
                <span class="title-line title-line-1">Welcome to</span>
                <span class="title-line highlight title-line-2">
                    <span>Andy's Room</span>
                </span>
            </h1>
            <p class="hero-slogan cinematic-slogan">
                <span class="quote-icon-left"><i class="fas fa-quote-left"></i></span>
                <span class="slogan-text">To Infinity and Beyond!</span>
                <span class="quote-icon-right"><i class="fas fa-quote-right"></i></span>
            </p>
            <p class="hero-description cinematic-description">
                Step into the magical world where toys come to life when humans aren't looking.
                Join Woody, Buzz, and all your favorite friends on an unforgettable adventure!
            </p>
            <div class="hero-buttons cinematic-buttons">
                <a href="#movies" class="btn btn-primary cinematic-btn btn-glow-effect">
                    <i class="fas fa-play"></i> Explore Movies
                    <span class="btn-shine"></span>
                </a>
                <a href="#characters" class="btn btn-secondary cinematic-btn btn-glow-effect">
                    <i class="fas fa-users"></i> Meet Characters
                    <span class="btn-shine"></span>
                </a>
            </div>
            <div class="scroll-indicator cinematic-scroll">
                <span>Scroll Down</span>
                <div class="scroll-arrow"><i class="fas fa-chevron-down"></i></div>
                <div class="scroll-line"></div>
            </div>
        </div>

        <div class="hero-visuals cinematic-hero-visuals">
            <div class="floating-element woody-float cinematic-float">
                <div class="character-placeholder woody-color cinematic-character">
                    <i class="fas fa-hat-cowboy"></i>
                    <div class="character-glow woody-glow"></div>
                </div>
            </div>
            <div class="floating-element buzz-float cinematic-float">
                <div class="character-placeholder buzz-color cinematic-character">
                    <i class="fas fa-rocket"></i>
                    <div class="character-glow buzz-glow"></div>
                </div>
            </div>
            <div class="floating-element jessie-float cinematic-float">
                <div class="character-placeholder jessie-color cinematic-character">
                    <i class="fas fa-horse-head"></i>
                    <div class="character-glow jessie-glow"></div>
                </div>
            </div>
            <div class="floating-element extra-float float-1"><div class="mini-toy-icon"><i class="fas fa-star"></i></div></div>
            <div class="floating-element extra-float float-2"><div class="mini-toy-icon"><i class="fas fa-circle"></i></div></div>
            <div class="floating-element extra-float float-3"><div class="mini-toy-icon"><i class="fas fa-cube"></i></div></div>
        </div>

        <div class="cinematic-lighting-overlay">
            <div class="spotlight spotlight-1"></div>
            <div class="spotlight spotlight-2"></div>
            <div class="lens-flare"></div>
        </div>
        <div class="hero-particles-container">
            <div class="particle particle-1"></div><div class="particle particle-2"></div>
            <div class="particle particle-3"></div><div class="particle particle-4"></div>
            <div class="particle particle-5"></div>
        </div>
    </section>

    <!-- ════════════════ ABOUT ════════════════ -->
    <section id="about" class="about-section cinematic-about">
        <div class="section-container">
            <div class="section-header cinematic-header">
                <span class="section-badge cinematic-badge-section">The Story</span>
                <h2 class="section-title cinematic-section-title">About Toy Story</h2>
                <div class="title-decoration cinematic-decoration">
                    <span class="decor-star"><i class="fas fa-star"></i></span>
                    <span class="decor-circle"></span>
                    <span class="decor-star"><i class="fas fa-star"></i></span>
                </div>
            </div>

            <div class="about-grid cinematic-about-grid">
                <div class="about-card main-card cinematic-card glassmorphism-card">
                    <div class="card-icon cinematic-card-icon">
                        <i class="fas fa-book-open"></i>
                        <div class="icon-glow"></div>
                    </div>
                    <h3 class="cinematic-card-title">The Beginning</h3>
                    <p class="cinematic-card-text">Released in 1995, Toy Story revolutionized animation as the first fully computer-animated feature film. Created by Pixar Animation Studios and distributed by Walt Disney Pictures, it introduced audiences to a world where toys have their own secret lives.</p>
                    <div class="card-expand-content">
                        <div class="expand-section">
                            <h4><i class="fas fa-lightbulb"></i> Pixar's Innovation</h4>
                            <p>Pixar's groundbreaking RenderMan software brought unprecedented realism to animated characters, setting a new standard for the industry.</p>
                        </div>
                        <div class="expand-section">
                            <h4><i class="fas fa-award"></i> Awards & Achievements</h4>
                            <p>Nominated for 3 Academy Awards including Best Original Screenplay, and won a Special Achievement Award for John Lasseter.</p>
                        </div>
                    </div>
                </div>

                <div class="about-card stat-card cinematic-stat-card glassmorphism-card">
                    <div class="stat-number cinematic-stat" data-target="<?php echo !empty($movies) ? count($movies) : 4; ?>">0</div>
                    <div class="stat-label">Feature Films</div>
                    <div class="stat-icon"><i class="fas fa-film"></i></div>
                    <div class="stat-glow"></div>
                </div>

                <div class="about-card stat-card cinematic-stat-card glassmorphism-card">
                    <div class="stat-number cinematic-stat" data-target="3">0</div>
                    <div class="stat-label">Box Office (Billions)</div>
                    <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                    <div class="stat-glow"></div>
                </div>

                <div class="about-card timeline-card cinematic-timeline-card glassmorphism-card">
                    <h3 class="cinematic-card-title"><i class="fas fa-history"></i> Timeline</h3>
                    <div class="timeline cinematic-timeline">
                        <?php foreach ($timeline as $t): ?>
                        <div class="timeline-item cinematic-timeline-item" data-year="<?php echo (int)$t['year']; ?>">
                            <span class="year"><?php echo (int)$t['year']; ?></span>
                            <span class="event"><?php echo htmlspecialchars($t['event'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <span class="timeline-detail"><?php echo htmlspecialchars($t['detail'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="about-card info-card cinematic-info-card glassmorphism-card">
                    <div class="card-icon cinematic-card-icon buzz-icon">
                        <i class="fas fa-rocket"></i>
                        <div class="icon-glow"></div>
                    </div>
                    <h3 class="cinematic-card-title">Cultural Impact</h3>
                    <p class="cinematic-card-text">Toy Story changed how we think about animation forever. Its themes of friendship, loyalty, and growing up resonated across generations.</p>
                    <ul class="info-list">
                        <li><i class="fas fa-check"></i> First fully CGI animated feature</li>
                        <li><i class="fas fa-check"></i> Launched Pixar's golden era</li>
                        <li><i class="fas fa-check"></i> Inspired countless animated films</li>
                    </ul>
                </div>

                <div class="about-card info-card cinematic-info-card glassmorphism-card">
                    <div class="card-icon cinematic-card-icon woody-icon">
                        <i class="fas fa-users"></i>
                        <div class="icon-glow"></div>
                    </div>
                    <h3 class="cinematic-card-title">Voice Cast Legacy</h3>
                    <p class="cinematic-card-text">Tom Hanks and Tim Allen became iconic voices of Woody and Buzz, bringing depth and humor that defined these beloved characters.</p>
                    <ul class="info-list">
                        <li><i class="fas fa-microphone"></i> Tom Hanks as Woody</li>
                        <li><i class="fas fa-microphone"></i> Tim Allen as Buzz</li>
                        <li><i class="fas fa-microphone"></i> Joan Cusack as Jessie</li>
                    </ul>
                </div>

                <div class="about-card trivia-card cinematic-trivia-card glassmorphism-card full-width">
                    <div class="card-icon cinematic-card-icon trivia-icon">
                        <i class="fas fa-lightbulb"></i>
                        <div class="icon-glow"></div>
                    </div>
                    <h3 class="cinematic-card-title">Fun Trivia &amp; Behind the Scenes</h3>
                    <div class="trivia-grid">
                        <div class="trivia-item"><i class="fas fa-star"></i><p>The original title was "You Are a Toy"</p></div>
                        <div class="trivia-item"><i class="fas fa-star"></i><p>Buzz Lightyear was named after Apollo astronaut Buzz Aldrin</p></div>
                        <div class="trivia-item"><i class="fas fa-star"></i><p>The carpet pattern in Sid's house appears in multiple Pixar films</p></div>
                        <div class="trivia-item"><i class="fas fa-star"></i><p>Woody was originally going to be a ventriloquist dummy</p></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ════════════════ MOVIES ════════════════ -->
    <section id="movies" class="movies-section">
        <div class="section-container">
            <div class="section-header">
                <span class="section-badge">The Collection</span>
                <h2 class="section-title">Movies</h2>
                <div class="title-decoration">
                    <span class="decor-star"><i class="fas fa-star"></i></span>
                    <span class="decor-circle"></span>
                    <span class="decor-star"><i class="fas fa-star"></i></span>
                </div>
            </div>

            <div class="movies-grid">
                <?php if (!empty($movies)): ?>
                    <?php foreach ($movies as $movie): ?>
                    <div class="movie-card" data-tilt>
                        <div class="movie-poster">
                            <img src="<?php echo htmlspecialchars($movie['poster_url'] ?? 'img/toystory1.webp', ENT_QUOTES, 'UTF-8'); ?>"
                                 alt="<?php echo htmlspecialchars($movie['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                 class="movie-poster-img"
                                 onerror="this.src='img/toystory1.webp'">
                            <div class="year-badge"><?php echo (int)($movie['release_year'] ?? 0); ?></div>
                            <div class="play-button"><i class="fas fa-play"></i></div>
                        </div>
                        <div class="movie-info">
                            <h3><?php echo htmlspecialchars($movie['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p class="movie-tagline"><?php echo htmlspecialchars($movie['tagline'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                            <div class="movie-meta">
                                <span><i class="fas fa-clock"></i> <?php echo (int)($movie['runtime'] ?? 0); ?> min</span>
                                <span><i class="fas fa-star"></i> <?php echo number_format((float)($movie['rating'] ?? 0), 1); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align:center; padding:3rem; color:rgba(255,255,255,.6); grid-column:1/-1;">
                        <i class="fas fa-film" style="font-size:3rem; margin-bottom:1rem; display:block;"></i>
                        <p>No movies to display yet. Check back soon!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ════════════════ CHARACTERS ════════════════ -->
    <section id="characters" class="characters-section">
        <div class="section-container">
            <div class="section-header">
                <span class="section-badge">Meet the Gang</span>
                <h2 class="section-title">Characters</h2>
                <div class="title-decoration">
                    <span class="decor-star"><i class="fas fa-star"></i></span>
                    <span class="decor-circle"></span>
                    <span class="decor-star"><i class="fas fa-star"></i></span>
                </div>
            </div>

            <div class="characters-grid">
                <?php if (!empty($characters)): ?>
                    <?php foreach ($characters as $char): ?>
                    <?php
                        $charName  = htmlspecialchars($char['name']        ?? '', ENT_QUOTES, 'UTF-8');
                        $charRole  = htmlspecialchars($char['role']         ?? '', ENT_QUOTES, 'UTF-8');
                        $charQuote = htmlspecialchars($char['quote']        ?? '', ENT_QUOTES, 'UTF-8');
                        $charDesc  = htmlspecialchars($char['description']  ?? '', ENT_QUOTES, 'UTF-8');
                        $charImg   = htmlspecialchars($char['avatar_url']   ?? 'img/woody.jpg', ENT_QUOTES, 'UTF-8');
                        $charClass = htmlspecialchars($char['css_class']    ?? 'woody', ENT_QUOTES, 'UTF-8');
                    ?>
                    <div class="character-card <?php echo $charClass; ?>">
                        <div class="character-avatar">
                            <img src="<?php echo $charImg; ?>"
                                 alt="<?php echo $charName; ?>"
                                 onerror="this.src='img/woody.jpg'">
                        </div>
                        <div class="character-overlay"></div>
                        <div class="character-info">
                            <h3><?php echo $charName; ?></h3>
                            <p class="character-role"><?php echo $charRole; ?></p>
                            <p class="character-quote"><?php echo $charQuote; ?></p>
                            <p class="character-description"><?php echo $charDesc; ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align:center; padding:3rem; color:rgba(255,255,255,.6); grid-column:1/-1;">
                        <i class="fas fa-users" style="font-size:3rem; margin-bottom:1rem; display:block;"></i>
                        <p>No characters to display yet. Check back soon!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ════════════════ FOOTER / CONTACT ════════════════ -->
    <footer id="contact" class="footer-section">
        <div class="toy-box-container">
            <div class="toy-box-lid"></div>
            <div class="footer-content">
                <div class="footer-grid">
                    <div class="footer-column brand-column">
                        <div class="footer-logo">
                            <i class="fas fa-box-open"></i>
                            <span>TOY STORY</span>
                        </div>
                        <p>A fan-made tribute to the beloved Pixar franchise that brought toys to life and captured our hearts.</p>
                        <div class="social-links">
                            <a href="https://www.facebook.com/PixarToyStory/videos/" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://x.com/toystory" class="social-icon"><i class="fab fa-twitter"></i></a>
                            <a href="https://www.instagram.com/toystory/" class="social-icon"><i class="fab fa-instagram"></i></a>
                            <a href="https://youtu.be/6F8k_cJ3JPA?si=VLN5jSF-sSsft2d-" class="social-icon"><i class="fab fa-youtube"></i></a>
                            <a href="https://www.tiktok.com/@ronydantis" class="social-icon"><i class="fab fa-tiktok"></i></a>
                        </div>
                    </div>
                    <div class="footer-column links-column">
                        <h4>Quick Links</h4>
                        <ul>
                            <li><a href="#home"><i class="fas fa-chevron-right"></i> Home</a></li>
                            <li><a href="#about"><i class="fas fa-chevron-right"></i> About</a></li>
                            <li><a href="#movies"><i class="fas fa-chevron-right"></i> Movies</a></li>
                            <li><a href="#characters"><i class="fas fa-chevron-right"></i> Characters</a></li>
                        </ul>
                    </div>
                    <div class="footer-column contact-column">
                        <h4>Get In Touch</h4>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>hello@toystoryfan.com</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Andy's Room, USA</span>
                        </div>
                        <div class="newsletter-form">
                            <input type="email" placeholder="Your email...">
                            <button type="button" class="btn-subscribe">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="footer-bottom">
                    <div class="footer-decorations">
                        <span class="mini-toy"><i class="fas fa-star"></i></span>
                        <span class="mini-toy"><i class="fas fa-cube"></i></span>
                        <span class="mini-toy"><i class="fas fa-rocket"></i></span>
                        <span class="mini-toy"><i class="fas fa-hat-cowboy"></i></span>
                    </div>
                    <p>&copy; <?php echo date('Y'); ?> Toy Story Fan Site. Made with <i class="fas fa-heart"></i> for fans everywhere.</p>
                    <p class="disclaimer">This is a fan-made website. Toy Story and all related characters are trademarks of Disney/Pixar.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
