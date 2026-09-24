// ===== BOUNCING TEXT ANIMATION =====
class BouncingText {
    constructor() {
        this.heading = document.getElementById('animatedHeading');
        if (!this.heading) return;
        
        // Define the words with highlighting - "Triple K" will be gold
        this.words = [
            { text: 'Find', highlight: false },
            { text: 'Your', highlight: false },
            { text: 'Dream', highlight: false },
            { text: 'Property', highlight: false },
            { text: 'with', highlight: false },
            { text: 'Triple', highlight: true },
            { text: 'K', highlight: true }
        ];
        
        this.allLetters = [];
        this.init();
    }
    
    init() {
        // Clear heading
        this.heading.innerHTML = '';
        
        // Build the words with letters
        this.words.forEach((wordData, wordIndex) => {
            const wordContainer = document.createElement('span');
            wordContainer.className = 'word-container';
            if (wordData.highlight) {
                wordContainer.classList.add('word-highlight');
            }
            
            const letters = wordData.text.split('');
            letters.forEach((letter, letterIndex) => {
                const span = document.createElement('span');
                span.className = 'letter';
                span.textContent = letter;
                span.dataset.wordIndex = wordIndex;
                span.dataset.letterIndex = letterIndex;
                // Staggered delay for each letter
                span.style.transitionDelay = `${(wordIndex * 0.12) + (letterIndex * 0.035)}s`;
                wordContainer.appendChild(span);
                this.allLetters.push(span);
            });
            
            this.heading.appendChild(wordContainer);
            
            // Add space between words (except after last word)
            if (wordIndex < this.words.length - 1) {
                const space = document.createElement('span');
                space.className = 'word-space';
                space.innerHTML = '&nbsp;';
                this.heading.appendChild(space);
            }
        });
        
        // Start animation after a short delay
        setTimeout(() => {
            this.animateLetters();
        }, 300);
    }
    
    animateLetters() {
        // First wave - letters appear with bounce
        this.allLetters.forEach((letter, index) => {
            setTimeout(() => {
                letter.classList.add('visible');
                
                // Add bounce effect after appearing
                setTimeout(() => {
                    letter.classList.add('bounce');
                    setTimeout(() => {
                        letter.classList.remove('bounce');
                    }, 700);
                }, 300);
            }, index * 45);
        });
        
        // Second wave - all letters bounce together with colors
        const totalDelay = this.allLetters.length * 45 + 1800;
        setTimeout(() => {
            this.secondWave();
        }, totalDelay);
    }
    
    secondWave() {
        this.allLetters.forEach((letter, index) => {
            setTimeout(() => {
                // Check if it's a highlighted word (Triple K)
                const isHighlighted = letter.closest('.word-highlight');
                
                if (!isHighlighted) {
                    // Non-highlighted letters get random colors
                    letter.style.color = this.getRandomColor();
                }
                
                letter.classList.add('bounce-again');
                setTimeout(() => {
                    letter.classList.remove('bounce-again');
                    // Reset color after animation
                    setTimeout(() => {
                        if (!isHighlighted) {
                            letter.style.color = '';
                        }
                    }, 300);
                }, 600);
            }, index * 25);
        });
        
        // Repeat every 10 seconds
        setTimeout(() => {
            this.secondWave();
        }, 10000);
    }
    
    getRandomColor() {
        const colors = [
            '#d69e2e', '#ecc94b', '#f6ad55', '#fc8181', 
            '#68d391', '#63b3ed', '#9f7aea', '#f687b3',
            '#4fd1c5', '#f6e05e', '#fc8181', '#b794f4',
            '#81e6d9', '#fbd38d', '#fbb6ce', '#d6bcfa'
        ];
        return colors[Math.floor(Math.random() * colors.length)];
    }
}

// ===== SHAPE ANIMATION =====
class ShapeAnimator {
    constructor() {
        this.shape = document.getElementById('animatedShape');
        this.isSquare = true;
        this.init();
    }
    
    init() {
        if (!this.shape) return;
        this.startAnimation();
    }
    
    startAnimation() {
        this.shape.classList.add('square');
        
        setTimeout(() => {
            this.transformToOval();
        }, 2000);
        
        setInterval(() => {
            if (this.isSquare) {
                this.transformToOval();
            } else {
                this.transformToSquare();
            }
        }, 3000);
    }
    
    transformToOval() {
        this.shape.classList.remove('square');
        this.shape.classList.add('oval');
        this.isSquare = false;
        
        this.shape.style.transform = 'scale(1.03)';
        setTimeout(() => {
            this.shape.style.transform = 'scale(1)';
        }, 300);
    }
    
    transformToSquare() {
        this.shape.classList.remove('oval');
        this.shape.classList.add('square');
        this.isSquare = true;
        
        this.shape.style.transform = 'scale(0.97)';
        setTimeout(() => {
            this.shape.style.transform = 'scale(1)';
        }, 300);
    }
}

// ===== SMOOTH SCROLL =====
class SmoothScroll {
    constructor() {
        this.init();
    }
    
    init() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#') return;
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }
}

// ===== PARALLAX EFFECT =====
class ParallaxEffect {
    constructor() {
        this.hero = document.querySelector('.hero');
        if (!this.hero) return;
        this.init();
    }
    
    init() {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * 0.3;
            
            const content = this.hero.querySelector('.hero-content');
            if (content) {
                content.style.transform = `translateY(${rate * 0.1}px)`;
            }
            
            const imageWrapper = this.hero.querySelector('.hero-image-wrapper');
            if (imageWrapper) {
                imageWrapper.style.transform = `translateY(${-rate * 0.05}px)`;
            }
        }, { passive: true });
    }
}

// ===== SCROLL REVEAL =====
class ScrollReveal {
    constructor() {
        this.elements = document.querySelectorAll('.property-card, .testimonial-card, .value-card, .team-card');
        this.init();
    }
    
    init() {
        if (this.elements.length === 0) return;
        
        this.elements.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(40px)';
            el.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
        });
        
        window.addEventListener('scroll', () => {
            this.checkVisibility();
        }, { passive: true });
        
        setTimeout(() => {
            this.checkVisibility();
        }, 300);
    }
    
    checkVisibility() {
        const windowHeight = window.innerHeight;
        const windowTop = window.scrollY;
        const windowBottom = windowTop + windowHeight;
        
        this.elements.forEach(el => {
            const rect = el.getBoundingClientRect();
            const elementTop = rect.top + windowTop;
            const elementBottom = elementTop + rect.height;
            
            if (elementBottom > windowTop + 100 && elementTop < windowBottom - 100) {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }
        });
    }
}

// ===== COUNTER ANIMATION =====
class CounterAnimation {
    constructor() {
        this.counters = document.querySelectorAll('.counter');
        if (this.counters.length === 0) return;
        this.animated = false;
        this.init();
    }
    
    init() {
        window.addEventListener('scroll', () => {
            if (!this.animated) {
                this.checkVisibility();
            }
        }, { passive: true });
    }
    
    checkVisibility() {
        const windowHeight = window.innerHeight;
        const windowTop = window.scrollY;
        const windowBottom = windowTop + windowHeight;
        
        this.counters.forEach(counter => {
            const rect = counter.getBoundingClientRect();
            const elementTop = rect.top + windowTop;
            
            if (elementTop < windowBottom - 100) {
                this.animateCounter(counter);
                this.animated = true;
            }
        });
    }
    
    animateCounter(counter) {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000;
        const start = performance.now();
        
        const update = (currentTime) => {
            const elapsed = currentTime - start;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = Math.floor(eased * target);
            
            counter.textContent = current.toLocaleString();
            
            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                counter.textContent = target.toLocaleString();
            }
        };
        
        requestAnimationFrame(update);
    }
}

// ===== NAVBAR SCROLL =====
class NavbarScroll {
    constructor() {
        this.navbar = document.querySelector('.header');
        if (!this.navbar) return;
        this.init();
    }
    
    init() {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                this.navbar.classList.add('scrolled');
            } else {
                this.navbar.classList.remove('scrolled');
            }
        }, { passive: true });
    }
}

// ===== CHATBOT =====
class Chatbot {
    constructor() {
        this.sessionId = this.getSessionId();
        this.window = document.getElementById('chatbotWindow');
        this.messages = document.getElementById('chatMessages');
        this.input = document.getElementById('chatInput');
        this.sendBtn = document.getElementById('chatSendBtn');
        this.toggleBtn = document.getElementById('chatbotToggle');
        this.closeBtn = document.getElementById('chatClose');
        this.isOpen = false;
        this.init();
    }
    
    getSessionId() {
        let sessionId = sessionStorage.getItem('chat_session_id');
        if (!sessionId) {
            sessionId = 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            sessionStorage.setItem('chat_session_id', sessionId);
        }
        return sessionId;
    }
    
    init() {
        this.toggleBtn.addEventListener('click', () => this.toggle());
        this.closeBtn.addEventListener('click', () => this.close());
        this.sendBtn.addEventListener('click', () => this.sendMessage());
        this.input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.sendMessage();
        });
        
        setTimeout(() => {
            this.addMessage('bot', 'Hello! Welcome to Triple K Properties. How can I assist you with finding your dream property today?');
        }, 500);
    }
    
    toggle() {
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }
    
    open() {
        this.window.classList.add('active');
        this.isOpen = true;
        this.input.focus();
    }
    
    close() {
        this.window.classList.remove('active');
        this.isOpen = false;
    }
    
    addMessage(type, text) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chatbot-message ${type}`;
        messageDiv.textContent = text;
        this.messages.appendChild(messageDiv);
        this.messages.scrollTop = this.messages.scrollHeight;
    }
    
    async sendMessage() {
        const message = this.input.value.trim();
        if (!message) return;
        
        this.addMessage('user', message);
        this.input.value = '';
        this.input.disabled = true;
        this.sendBtn.disabled = true;
        
        try {
            const response = await fetch('chatbot.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `message=${encodeURIComponent(message)}&session_id=${this.sessionId}`
            });
            
            const data = await response.json();
            this.addMessage('bot', data.response);
        } catch (error) {
            this.addMessage('bot', 'Sorry, I am having trouble connecting. Please try again later.');
        }
        
        this.input.disabled = false;
        this.sendBtn.disabled = false;
        this.input.focus();
    }
}

// ===== HERO SLIDESHOW =====
class HeroSlideshow {
    constructor() {
        this.slides = document.querySelectorAll('.hero-slideshow .slide');
        this.currentIndex = 0;
        this.isAnimating = false;
        this.interval = null;
        this.delay = 5000; // 5 seconds between slides
        this.init();
    }
    
    init() {
        if (this.slides.length === 0) return;
        
        // Set first slide as active (now the third image from original)
        this.slides.forEach((slide, index) => {
            if (index === 0) {
                slide.classList.add('active');
            } else {
                slide.classList.remove('active');
                slide.classList.remove('exit');
            }
        });
        
        // Create indicators
        this.createIndicators();
        
        // Start auto-play
        this.startSlideshow();
        
        // Pause on hover
        const slideshow = document.querySelector('.hero-slideshow');
        if (slideshow) {
            slideshow.addEventListener('mouseenter', () => this.pauseSlideshow());
            slideshow.addEventListener('mouseleave', () => this.resumeSlideshow());
        }
    }
    
    createIndicators() {
        const indicatorsContainer = document.createElement('div');
        indicatorsContainer.className = 'slide-indicators';
        
        this.slides.forEach((_, index) => {
            const indicator = document.createElement('div');
            indicator.className = 'slide-indicator';
            if (index === 0) indicator.classList.add('active');
            indicator.dataset.index = index;
            indicator.addEventListener('click', () => this.goToSlide(index));
            indicatorsContainer.appendChild(indicator);
        });
        
        const slideshow = document.querySelector('.hero-slideshow');
        if (slideshow) {
            slideshow.appendChild(indicatorsContainer);
        }
        
        this.indicators = document.querySelectorAll('.slide-indicator');
    }
    
    startSlideshow() {
        if (this.interval) clearInterval(this.interval);
        this.interval = setInterval(() => {
            this.nextSlide();
        }, this.delay);
    }
    
    pauseSlideshow() {
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
        }
    }
    
    resumeSlideshow() {
        if (!this.interval) {
            this.interval = setInterval(() => {
                this.nextSlide();
            }, this.delay);
        }
    }
    
    nextSlide() {
        if (this.isAnimating) return;
        const nextIndex = (this.currentIndex + 1) % this.slides.length;
        this.goToSlide(nextIndex);
    }
    
    goToSlide(index) {
        if (this.isAnimating || index === this.currentIndex) return;
        this.isAnimating = true;
        
        const currentSlide = this.slides[this.currentIndex];
        const nextSlide = this.slides[index];
        
        // Get the direction class from the next slide
        const directionClass = this.getDirectionClass(nextSlide);
        
        // Exit current slide
        currentSlide.classList.remove('active');
        currentSlide.classList.add('exit');
        
        // Prepare next slide
        nextSlide.style.display = 'block';
        nextSlide.classList.remove('exit');
        nextSlide.classList.add('active');
        
        // Force reflow
        void nextSlide.offsetWidth;
        
        // Remove exit class from current after animation
        setTimeout(() => {
            currentSlide.classList.remove('exit');
            currentSlide.style.display = 'none';
        }, 1200);
        
        // Update indicators
        this.updateIndicators(index);
        
        // Reset animation flag
        setTimeout(() => {
            this.isAnimating = false;
        }, 1500);
        
        this.currentIndex = index;
    }
    
    getDirectionClass(slide) {
        // Get the direction class from the slide
        const classes = slide.className.split(' ');
        for (let cls of classes) {
            if (cls.startsWith('slide-') && cls !== 'slide') {
                return cls;
            }
        }
        return 'slide-top'; // fallback
    }
    
    updateIndicators(index) {
        if (this.indicators) {
            this.indicators.forEach((ind, i) => {
                ind.classList.toggle('active', i === index);
            });
        }
    }
}

// ===== IMAGE PRELOADER =====
class ImagePreloader {
    constructor() {
        this.images = [];
        this.loaded = 0;
        this.init();
    }
    
    init() {
        // Get all background images from slides
        const slides = document.querySelectorAll('.hero-slideshow .slide');
        slides.forEach(slide => {
            const style = window.getComputedStyle(slide);
            const bgImage = style.backgroundImage;
            if (bgImage && bgImage !== 'none') {
                const url = bgImage.replace(/url\(["']?|["']?\)/g, '');
                if (url) {
                    this.images.push(url);
                }
            }
        });
        
        this.preload();
    }
    
    preload() {
        if (this.images.length === 0) return;
        
        this.images.forEach((url, index) => {
            const img = new Image();
            img.onload = () => {
                this.loaded++;
                if (this.loaded === this.images.length) {
                    // All images loaded, start slideshow
                    document.dispatchEvent(new Event('slideshowReady'));
                }
            };
            img.onerror = () => {
                this.loaded++;
                if (this.loaded === this.images.length) {
                    document.dispatchEvent(new Event('slideshowReady'));
                }
            };
            img.src = url;
        });
    }
}

// ===== FAQ ACCORDION =====
function initFaq() {
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        question.addEventListener('click', () => {
            const isActive = item.classList.contains('active');
            faqItems.forEach(i => i.classList.remove('active'));
            if (!isActive) {
                item.classList.add('active');
            }
        });
    });
}

// ===== MOBILE NAVIGATION =====
function initMobileNav() {
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('navMenu');
    
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });
    }
}

// ===== PROPERTY SEARCH =====
function initPropertySearch() {
    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const location = document.getElementById('searchLocation').value;
            const type = document.getElementById('propertyType').value;
            const minPrice = document.getElementById('minPrice').value;
            const maxPrice = document.getElementById('maxPrice').value;
            
            let url = 'properties.php?';
            if (location) url += `location=${encodeURIComponent(location)}&`;
            if (type && type !== 'all') url += `type=${type}&`;
            if (minPrice) url += `min_price=${minPrice}&`;
            if (maxPrice) url += `max_price=${maxPrice}`;
            
            window.location.href = url;
        });
    }
}

// ===== INITIALIZE =====
document.addEventListener('DOMContentLoaded', () => {
    initFaq();
    initMobileNav();
    initPropertySearch();
    new ShapeAnimator();
    new SmoothScroll();
    new ParallaxEffect();
    new ScrollReveal();
    new CounterAnimation();
    new NavbarScroll();
    new BouncingText();
    if (document.getElementById('chatbotWindow')) {
        new Chatbot();
    }
    
    // Initialize slideshow after preloading
    new ImagePreloader();
    
    // Start slideshow when images are ready or after a delay
    const startSlideshow = () => {
        new HeroSlideshow();
    };
    
    document.addEventListener('slideshowReady', startSlideshow);
    // Fallback: start after 2 seconds if images don't load
    setTimeout(startSlideshow, 2000);
});