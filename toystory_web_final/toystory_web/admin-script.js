/* ========================================
   TOY STORY ADMIN PANEL - JAVASCRIPT
   UI Interactivity Only (No Backend Logic)
   ======================================== */

document.addEventListener('DOMContentLoaded', function() {

    // ========================================
    // CUSTOM CURSOR (Optional for Admin)
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

        const interactiveElements = document.querySelectorAll('a, button, .nav-link, .btn-action');
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
    // LOGIN PAGE FUNCTIONALITY
    // ========================================
    
    // Password Toggle
    const passwordToggle = document.getElementById('passwordToggle');
    const passwordInput = document.getElementById('password');

    if (passwordToggle && passwordInput) {
        passwordToggle.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }

    // Login Form Submission
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.querySelector('.login-btn');
    const loginLoader = document.getElementById('loginLoader');
    const successToast = document.getElementById('successToast');
    const errorToast = document.getElementById('errorToast');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous messages
            if (successToast) successToast.classList.add('hidden');
            if (errorToast) errorToast.classList.add('hidden');
            
            // Show loading state
            loginBtn.classList.add('loading');
            
            // Simulate API call (replace with actual backend integration)
            setTimeout(() => {
                const username = document.getElementById('username').value;
                const password = document.getElementById('password').value;
                
                // Placeholder validation - replace with actual backend authentication
                if (username && password) {
                    // Success scenario
                    if (successToast) {
                        successToast.classList.remove('hidden');
                        // Redirect after delay (replace with actual redirect)
                        setTimeout(() => {
                            window.location.href = 'admin-dashboard.html';
                        }, 1500);
                    }
                } else {
                    // Error scenario
                    if (errorToast) {
                        errorToast.classList.remove('hidden');
                    }
                }
                
                // Hide loading state
                loginBtn.classList.remove('loading');
            }, 2000);
        });
    }

    // ========================================
    // DASHBOARD SIDEBAR TOGGLE
    // ========================================
    const sidebar = document.getElementById('adminSidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            
            const icon = this.querySelector('i');
            if (sidebar.classList.contains('collapsed')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-chevron-right');
            } else {
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-bars');
            }
        });
    }

    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 1024) {
            if (!sidebar.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        }
    });

    // ========================================
    // MODAL FUNCTIONALITY
    // ========================================
    
    // Add Movie Modal
    const addMovieBtn = document.getElementById('addMovieBtn');
    const addMovieModal = document.getElementById('addMovieModal');
    const closeMovieModal = document.getElementById('closeMovieModal');
    const cancelMovieBtn = document.getElementById('cancelMovieBtn');
    const addMovieForm = document.getElementById('addMovieForm');

    if (addMovieBtn && addMovieModal) {
        addMovieBtn.addEventListener('click', function() {
            addMovieModal.classList.remove('hidden');
        });

        const closeMovieModalFunc = function() {
            addMovieModal.classList.add('hidden');
            if (addMovieForm) addMovieForm.reset();
        };

        if (closeMovieModal) closeMovieModal.addEventListener('click', closeMovieModalFunc);
        if (cancelMovieBtn) cancelMovieBtn.addEventListener('click', closeMovieModalFunc);

        // Close on overlay click
        addMovieModal.addEventListener('click', function(e) {
            if (e.target === addMovieModal) {
                closeMovieModalFunc();
            }
        });

        // Form submission placeholder
        if (addMovieForm) {
            addMovieForm.addEventListener('submit', function(e) {
                e.preventDefault();
                // Placeholder: Add your backend integration here
                alert('Movie form submitted! (Integrate with backend)');
                closeMovieModalFunc();
            });
        }
    }

    // Add Character Modal
    const addCharacterBtn = document.getElementById('addCharacterBtn');
    const addCharacterModal = document.getElementById('addCharacterModal');
    const closeCharacterModal = document.getElementById('closeCharacterModal');
    const cancelCharacterBtn = document.getElementById('cancelCharacterBtn');
    const addCharacterForm = document.getElementById('addCharacterForm');

    if (addCharacterBtn && addCharacterModal) {
        addCharacterBtn.addEventListener('click', function() {
            addCharacterModal.classList.remove('hidden');
        });

        const closeCharacterModalFunc = function() {
            addCharacterModal.classList.add('hidden');
            if (addCharacterForm) addCharacterForm.reset();
        };

        if (closeCharacterModal) closeCharacterModal.addEventListener('click', closeCharacterModalFunc);
        if (cancelCharacterBtn) cancelCharacterBtn.addEventListener('click', closeCharacterModalFunc);

        // Close on overlay click
        addCharacterModal.addEventListener('click', function(e) {
            if (e.target === addCharacterModal) {
                closeCharacterModalFunc();
            }
        });

        // Form submission placeholder
        if (addCharacterForm) {
            addCharacterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                // Placeholder: Add your backend integration here
                alert('Character form submitted! (Integrate with backend)');
                closeCharacterModalFunc();
            });
        }
    }

    // ========================================
    // STATS COUNTER ANIMATION
    // ========================================
    const statNumbers = document.querySelectorAll('.stat-number[data-target]');

    const animateCounter = (element) => {
        const target = parseInt(element.getAttribute('data-target'));
        const duration = 2000;
        const increment = target / (duration / 16);
        let current = 0;

        const updateCounter = () => {
            current += increment;
            if (current < target) {
                element.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target;
            }
        };

        updateCounter();
    };

    // Trigger animation when stats are visible
    const observerOptions = {
        threshold: 0.5
    };

    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                statsObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    statNumbers.forEach(stat => {
        statsObserver.observe(stat);
    });

    // ========================================
    // SEARCH & FILTER FUNCTIONALITY (UI Only)
    // ========================================
    
    // Global Search with Debounce
    const globalSearch = document.getElementById('globalSearch');
    
    if (globalSearch) {
        let searchTimeout;
        globalSearch.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value;
            
            searchTimeout = setTimeout(() => {
                console.log('Global search:', query);
                // Placeholder: Add your search implementation here
            }, 300);
        });
    }

    // Movie Search Filter
    const movieSearch = document.getElementById('movieSearch');
    if (movieSearch) {
        movieSearch.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#moviesTableBody tr');
            
            tableRows.forEach(row => {
                const title = row.cells[1]?.textContent.toLowerCase() || '';
                if (title.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Character Search Filter
    const characterSearch = document.getElementById('characterSearch');
    if (characterSearch) {
        characterSearch.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#charactersTableBody tr');
            
            tableRows.forEach(row => {
                const name = row.cells[2]?.textContent.toLowerCase() || '';
                if (name.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Movie Rating Filter
    const movieFilter = document.getElementById('movieFilter');
    if (movieFilter) {
        movieFilter.addEventListener('change', function() {
            const rating = this.value;
            const tableRows = document.querySelectorAll('#moviesTableBody tr');
            
            tableRows.forEach(row => {
                if (!rating) {
                    row.style.display = '';
                } else {
                    const cellRating = row.cells[3]?.textContent.trim() || '';
                    row.style.display = cellRating === rating ? '' : 'none';
                }
            });
        });
    }

    // Character Role Filter
    const characterFilter = document.getElementById('characterFilter');
    if (characterFilter) {
        characterFilter.addEventListener('change', function() {
            const role = this.value;
            const tableRows = document.querySelectorAll('#charactersTableBody tr');
            
            tableRows.forEach(row => {
                if (!role) {
                    row.style.display = '';
                } else {
                    const cellRole = row.cells[3]?.textContent.trim() || '';
                    row.style.display = cellRole === role ? '' : 'none';
                }
            });
        });
    }

    // ========================================
    // ACTION BUTTONS (Edit/Delete Placeholders)
    // ========================================
    
    // Edit buttons
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            const id = row.cells[0]?.textContent;
            const tableId = this.closest('tbody').id;
            const type = tableId === 'moviesTableBody' ? 'Movie' : 'Character';
            
            // Placeholder: Open edit modal or navigate to edit page
            alert(`Edit ${type} #${id} (Integrate with backend)`);
        });
    });

    // Delete buttons
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            const id = row.cells[0]?.textContent;
            const tableId = this.closest('tbody').id;
            const type = tableId === 'moviesTableBody' ? 'Movie' : 'Character';
            
            // Confirm deletion
            if (confirm(`Are you sure you want to delete ${type} #${id}?`)) {
                // Placeholder: Add your delete logic here
                row.style.opacity = '0.5';
                setTimeout(() => {
                    row.remove();
                    // Check if table is empty and show empty state
                    checkEmptyState(tableId);
                }, 300);
            }
        });
    });

    // Check and show empty state
    function checkEmptyState(tableId) {
        const tableBody = document.getElementById(tableId);
        const emptyStateId = tableId === 'moviesTableBody' ? 'moviesEmptyState' : 'charactersEmptyState';
        const emptyState = document.getElementById(emptyStateId);
        
        if (tableBody && emptyState) {
            const visibleRows = tableBody.querySelectorAll('tr[style=""], tr:not([style*="display: none"])');
            if (visibleRows.length === 0) {
                emptyState.classList.remove('hidden');
            }
        }
    }

    // ========================================
    // PAGINATION (UI Placeholder)
    // ========================================
    document.querySelectorAll('.pagination-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (this.disabled) return;
            
            // Remove active class from all buttons
            this.parentElement.querySelectorAll('.pagination-btn').forEach(b => {
                b.classList.remove('active');
            });
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Placeholder: Load corresponding page data
            const pageNum = this.textContent.trim();
            if (pageNum && !isNaN(pageNum)) {
                console.log('Navigate to page:', pageNum);
            }
        });
    });

    // ========================================
    // NOTIFICATION BUTTON (Placeholder)
    // ========================================
    const notificationBtn = document.getElementById('notificationBtn');
    if (notificationBtn) {
        notificationBtn.addEventListener('click', function() {
            alert('Notifications panel (Integrate with backend)');
        });
    }

    // ========================================
    // PROFILE AVATAR (Placeholder)
    // ========================================
    const profileAvatar = document.querySelector('.profile-avatar');
    if (profileAvatar) {
        profileAvatar.addEventListener('click', function() {
            alert('Profile menu (Integrate with backend)');
        });
    }

    // ========================================
    // ACTIVE NAV LINK ON SCROLL (Dashboard)
    // ========================================
    const navLinks = document.querySelectorAll('.sidebar-nav .nav-link');
    const sections = document.querySelectorAll('.content-section[id]');

    if (navLinks.length > 0 && sections.length > 0) {
        window.addEventListener('scroll', () => {
            const scrollY = window.pageYOffset;

            sections.forEach(section => {
                const sectionHeight = section.offsetHeight;
                const sectionTop = section.offsetTop - 200;
                const sectionId = section.getAttribute('id');

                if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                    navLinks.forEach(link => {
                        link.parentElement.classList.remove('active');
                        if (link.getAttribute('href') === '#' + sectionId) {
                            link.parentElement.classList.add('active');
                        }
                    });
                }
            });
        });
    }

    // ========================================
    // SMOOTH SCROLL FOR NAVIGATION LINKS
    // ========================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    
                    // Close mobile sidebar if open
                    if (sidebar && window.innerWidth <= 1024) {
                        sidebar.classList.remove('active');
                    }
                }
            }
        });
    });

    console.log('Toy Story Admin Panel initialized successfully!');
});
