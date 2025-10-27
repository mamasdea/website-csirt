import "./bootstrap";

// ============================================
// THEME TOGGLE (Dark/Light Mode)
// ============================================
document.addEventListener("DOMContentLoaded", () => {
    const themeToggle = document.getElementById("theme-toggle");
    const html = document.documentElement;

    // Check saved theme or default to dark
    const currentTheme = localStorage.getItem("theme") || "dark";
    if (currentTheme === "light") {
        html.classList.add("light");
    }

    if (themeToggle) {
        themeToggle.addEventListener("click", () => {
            html.classList.toggle("light");
            const theme = html.classList.contains("light") ? "light" : "dark";
            localStorage.setItem("theme", theme);

            // Update icon (akan dihandle oleh Lucide)
            updateThemeIcon(theme);
        });
    }

    // Update theme icon
    function updateThemeIcon(theme) {
        const icon = themeToggle?.querySelector("[data-lucide]");
        if (icon) {
            icon.setAttribute(
                "data-lucide",
                theme === "light" ? "moon" : "sun"
            );
            // Reinitialize Lucide icons if available
            if (typeof lucide !== "undefined") {
                lucide.createIcons();
            }
        }
    }
});

// ============================================
// SCROLL TO TOP WITH PROGRESS RING
// ============================================
window.addEventListener("load", () => {
    const scrollToTopWrap = document.getElementById("scroll-to-top-wrap");
    const scrollToTopBtn = document.getElementById("scroll-to-top");
    const progressCircle = document.querySelector(
        "#progress-ring circle.progress"
    );
    const circumference = 163.36; // 2 * π * r (r=26)

    if (scrollToTopBtn && progressCircle) {
        window.addEventListener("scroll", () => {
            const scrollTop =
                window.pageYOffset || document.documentElement.scrollTop;
            const docHeight =
                document.documentElement.scrollHeight - window.innerHeight;
            const scrollPercent = scrollTop / docHeight;
            const offset = circumference - scrollPercent * circumference;

            progressCircle.style.strokeDashoffset = offset;

            // Show/hide button
            if (scrollTop > 300) {
                scrollToTopWrap?.classList.remove("hidden");
            } else {
                scrollToTopWrap?.classList.add("hidden");
            }
        });

        scrollToTopBtn.addEventListener("click", () => {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    }
});

// ============================================
// HERO SLIDER
// ============================================
let currentSlide = 0;
let autoSlideInterval = null;

window.initHeroSlider = function () {
    const slides = document.querySelectorAll(".hero-slide");
    const boxes = document.querySelectorAll(".hero-box");

    if (slides.length === 0) return;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.style.opacity = i === index ? "1" : "0";
            slide.style.zIndex = i === index ? "1" : "0";
        });
        boxes.forEach((box, i) => {
            box.classList.toggle("active", i === index);
        });
    }

    window.nextSlide = function () {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    };

    window.prevSlide = function () {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(currentSlide);
    };

    window.goToSlide = function (index) {
        currentSlide = index;
        showSlide(currentSlide);
        resetAutoSlide();
    };

    // Auto slide every 5 seconds
    function startAutoSlide() {
        autoSlideInterval = setInterval(() => {
            window.nextSlide();
        }, 5000);
    }

    function resetAutoSlide() {
        if (autoSlideInterval) {
            clearInterval(autoSlideInterval);
        }
        startAutoSlide();
    }

    // Initialize
    showSlide(0);
    startAutoSlide();

    // Add click handlers to boxes
    boxes.forEach((box, index) => {
        box.addEventListener("click", () => {
            window.goToSlide(index);
        });
    });
};

// Initialize hero slider when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
    if (typeof window.initHeroSlider === "function") {
        window.initHeroSlider();
    }
});

// ============================================
// DROPDOWN MENU
// ============================================
document.addEventListener("DOMContentLoaded", () => {
    const dropdowns = document.querySelectorAll(".dropdown-hover");

    dropdowns.forEach((dropdown) => {
        const menu = dropdown.querySelector(".dropdown-menu");

        if (menu) {
            dropdown.addEventListener("mouseenter", () => {
                menu.style.display = "block";
            });

            dropdown.addEventListener("mouseleave", () => {
                menu.style.display = "none";
            });
        }
    });
});

// ============================================
// MOBILE MENU TOGGLE
// ============================================
window.toggleMobileMenu = function () {
    const mobileMenu = document.getElementById("mobile-menu");
    if (mobileMenu) {
        mobileMenu.classList.toggle("hidden");
    }
};

// ============================================
// SMOOTH SCROLL FOR ANCHOR LINKS
// ============================================
document.addEventListener("DOMContentLoaded", () => {
    const anchorLinks = document.querySelectorAll('a[href^="#"]');

    anchorLinks.forEach((link) => {
        link.addEventListener("click", (e) => {
            const href = link.getAttribute("href");
            if (href === "#" || href === "#!") return;

            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: "smooth", block: "start" });
            }
        });
    });
});

// ============================================
// INITIALIZE LUCIDE ICONS
// ============================================
window.addEventListener("load", () => {
    if (typeof lucide !== "undefined") {
        lucide.createIcons();
    }
});

// Re-initialize Lucide icons after dynamic content changes
window.reinitIcons = function () {
    if (typeof lucide !== "undefined") {
        lucide.createIcons();
    }
};

// ============================================
// CARD ANIMATION ON SCROLL (Optional)
// ============================================
const observeCards = () => {
    const cards = document.querySelectorAll(".card-elev");

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = "1";
                    entry.target.style.transform = "translateY(0)";
                }
            });
        },
        { threshold: 0.1 }
    );

    cards.forEach((card) => {
        card.style.opacity = "0";
        card.style.transform = "translateY(20px)";
        card.style.transition = "opacity 0.6s ease, transform 0.6s ease";
        observer.observe(card);
    });
};

document.addEventListener("DOMContentLoaded", observeCards);

// ============================================
// UTILITY: Debounce Function
// ============================================
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Expose to window for global access
window.debounce = debounce;

// ============================================
// EXPORT FUNCTIONS (jika diperlukan di view lain)
// ============================================
export { observeCards, debounce };
