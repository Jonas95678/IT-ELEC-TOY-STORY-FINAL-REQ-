
/* ========================================
   TOY STORY FAN SITE - CINEMATIC JAVASCRIPT
   Ultra-Premium Interactive Experience
   ======================================== */

document.addEventListener('DOMContentLoaded', function() {

    // ========================================
    // CINEMATIC LOADER
    // ========================================
    const cinematicLoader = document.getElementById('cinematicLoader');

    setTimeout(() => {
        cinematicLoader.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 3000);

    // ========================================
    // CUSTOM CURSOR
    // ========================================
    const customCursor = document.getElementById('customCursor');
    const cursorGlow = document.getElementById('cursorGlow');

    if (customCursor && cursorGlow) {
        document.addEventListener('mousemove', (e) => {
            customCursor.style.left = e.clientX + 'px';
            customCursor.style.top = e.clientY + 'px';
            cursorGlow.style.left = e.clientX + 'px';
            cursorGlow.style.top = e.clientY + 'px';
        });

        const interactiveElements = document.querySelectorAll('a, button, .character-card, .movie-card');

        interactiveElements.forEach(el => {
            el.addEventListener('mouseenter', () => {
                customCursor.classList.add('hovered');
            });

            el.addEventListener('mouseleave', () => {
                customCursor.classList.remove('hovered');
            });
        });
    }

    // ========================================
    // NAVBAR SCROLL EFFECT
    // ========================================
    const navbar = document.querySelector('.navbar');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 100) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // ========================================
    // MOBILE NAVIGATION
    // ========================================
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    const navLinks = document.querySelectorAll('.nav-link');
    const navActions = document.getElementById('navActions');

    if (hamburger) {
        hamburger.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            hamburger.classList.toggle('active');
            // Toggle visibility of nav actions on mobile
            if (navActions && window.innerWidth <= 768) {
                navActions.classList.toggle('active');
            }
        });
    }

    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            navMenu.classList.remove('active');
            hamburger.classList.remove('active');
            if (navActions) {
                navActions.classList.remove('active');
            }
        });
    });

    // ========================================
    // ACTIVE NAV LINK ON SCROLL
    // ========================================
    const sections = document.querySelectorAll('section[id]');

    window.addEventListener('scroll', () => {
        const scrollY = window.pageYOffset;

        sections.forEach(section => {
            const sectionHeight = section.offsetHeight;
            const sectionTop = section.offsetTop - 200;
            const sectionId = section.getAttribute('id');

            if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === '#' + sectionId) {
                        link.classList.add('active');
                    }
                });
            }
        });
    });

    // ========================================
    // SMOOTH SCROLL FOR ANCHOR LINKS
    // ========================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // ========================================
    // SCROLL REVEAL ANIMATIONS
    // ========================================
    const revealElements = document.querySelectorAll('.about-card, .movie-card, .character-card, .section-header');

    const revealOnScroll = () => {
        revealElements.forEach(el => {
            const elementTop = el.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;

            if (elementTop < windowHeight - 100) {
                el.classList.add('reveal', 'active');
            }
        });
    };

    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll();

    // ========================================
    // ANIMATED STAT COUNTERS
    // ========================================
    const statNumbers = document.querySelectorAll('.cinematic-stat');

    const animateStats = () => {
        statNumbers.forEach(stat => {
            const elementTop = stat.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;

            if (elementTop < windowHeight - 100 && !stat.classList.contains('animated')) {
                const target = parseInt(stat.getAttribute('data-target'));
                let count = 0;

                const updateCount = () => {
                    const increment = target / 50;

                    if (count < target) {
                        count += increment;
                        stat.textContent = Math.ceil(count);
                        setTimeout(updateCount, 40);
                    } else {
                        stat.textContent = target;
                    }
                };

                updateCount();
                stat.classList.add('animated');
            }
        });
    };

    window.addEventListener('scroll', animateStats);
    animateStats();

    // ========================================
    // GALLERY FILTERING - REMOVED (Gallery section deleted)
    // ========================================

    // ========================================
    // LIGHTBOX FUNCTIONALITY - REMOVED (Gallery section deleted)
    // ========================================

    // ========================================
    // PARALLAX EFFECT FOR HERO SECTION
    // ========================================
    const heroSection = document.querySelector('.hero-section');
    const parallaxLayers = document.querySelectorAll('.hero-parallax-layer');

    if (heroSection) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const heroHeight = heroSection.offsetHeight;

            if (scrolled < heroHeight) {
                parallaxLayers.forEach((layer, index) => {
                    const speed = (index + 1) * 0.1;
                    layer.style.transform = `translateY(${scrolled * speed}px)`;
                });
            }
        });
    }

    // ========================================
    // BUTTON RIPPLE EFFECT
    // ========================================
    const buttons = document.querySelectorAll('.btn, .filter-btn, .btn-subscribe');

    buttons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const ripple = document.createElement('span');
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.backgroundColor = 'rgba(255, 255, 255, 0.5)';
            ripple.style.width = '100px';
            ripple.style.height = '100px';
            ripple.style.left = x - 50 + 'px';
            ripple.style.top = y - 50 + 'px';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'rippleEffect 0.6s linear';
            ripple.style.pointerEvents = 'none';

            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Add ripple animation style
    const style = document.createElement('style');
    style.textContent = `
        @keyframes rippleEffect {
            to { transform: scale(4); opacity: 0; }
        }
    `;
    document.head.appendChild(style);

    // ========================================
    // CHARACTER CARD TILT EFFECT
    // ========================================
    const tiltCards = document.querySelectorAll('.character-card, .movie-card');

    tiltCards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = (y - centerY) / 10;
            const rotateY = (centerX - x) / 10;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-10px)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0)';
        });
    });

    // ========================================
    // TIMELINE INTERACTIVE HIGHLIGHT
    // ========================================
    const timelineItems = document.querySelectorAll('.timeline-item');

    timelineItems.forEach(item => {
        item.addEventListener('mouseenter', () => {
            timelineItems.forEach(i => {
                if (i !== item) {
                    i.style.opacity = '0.5';
                }
            });
            item.style.transform = 'scale(1.1)';
            item.style.zIndex = '10';
        });

        item.addEventListener('mouseleave', () => {
            timelineItems.forEach(i => {
                i.style.opacity = '1';
                i.style.transform = 'scale(1)';
                i.style.zIndex = '1';
            });
        });
    });

    // ========================================
    // FLOATING ELEMENTS MOUSE PARALLAX
    // ========================================
    const floatingElements = document.querySelectorAll('.floating-element');

    window.addEventListener('mousemove', (e) => {
        const mouseX = e.clientX / window.innerWidth - 0.5;
        const mouseY = e.clientY / window.innerHeight - 0.5;

        floatingElements.forEach((el, index) => {
            const speed = (index + 1) * 20;
            const x = mouseX * speed;
            const y = mouseY * speed;
            el.style.transform = `translate(${x}px, ${y}px)`;
        });
    });

    // ========================================
    // CONSOLE EASTER EGG
    // ========================================
    console.log('%c🤠 Welcome to Andy\'s Room! 🚀', 'font-size: 20px; color: #F4C542; text-shadow: 0 0 10px rgba(244, 197, 66, 0.5);');
    console.log('%cTo Infinity and Beyond!', 'font-size: 14px; color: #2ECC71;');
    console.log('%cMade with ❤️ for Pixar fans everywhere.', 'font-size: 12px; color: #9B59B6;');
});