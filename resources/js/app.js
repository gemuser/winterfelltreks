// Winterfell Treks & Expedition - Main JavaScript Entry Point
import './bootstrap';

// Alpine.js Global Components Registration
function registerAlpineComponents() {
    // Mobile Navigation
    Alpine.data('mobileNav', () => ({
        open: false,
        toggle() {
            this.open = !this.open;
            document.body.style.overflow = this.open ? 'hidden' : '';
        },
        close() {
            this.open = false;
            document.body.style.overflow = '';
        }
    }));

    // Enquiry Form Handler
    Alpine.data('enquiryForm', () => ({
        submitting: false,
        formData: {},
        
        async submit(form) {
            this.submitting = true;
            const formData = new FormData(form);
            
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || ''
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.location.href = data.redirect;
                } else if (data.errors) {
                    this.showErrors(data.errors);
                }
            } catch (error) {
                console.error('Submission error:', error);
                this.showError('An error occurred. Please try again.');
            } finally {
                this.submitting = false;
            }
        },
        
        showErrors(errors) {
            Object.keys(errors).forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('border-red-500');
                    let errorEl = input.parentElement.querySelector('.error-message');
                    if (!errorEl) {
                        errorEl = document.createElement('p');
                        errorEl.className = 'error-message mt-1 text-sm text-red-600';
                        input.parentElement.appendChild(errorEl);
                    }
                    errorEl.textContent = errors[field][0];
                }
            });
        },
        
        showError(message) {
            alert(message); // Fallback
        }
    }));

    // FAQ Accordion
    Alpine.data('faqAccordion', () => ({
        init() {
            // Allow multiple open by default
            this.$refs.accordion?.querySelectorAll('details').forEach(detail => {
                detail.addEventListener('toggle', () => {
                    // Optional: close others when one opens
                    // this.$refs.accordion.querySelectorAll('details').forEach(d => {
                    //     if (d !== detail) d.removeAttribute('open');
                    // });
                });
            });
        }
    }));

    // Testimonial Slider
    Alpine.data('testimonialSlider', (autoplay = true, interval = 5000) => ({
        current: 0,
        slides: [],
        timer: null,
        track: null,
        dots: null,
        
        init() {
            this.track = this.$refs.track;
            this.dots = this.$refs.dots;
            this.slides = this.track?.querySelectorAll('.testimonial-slide') || [];
            
            if (autoplay && this.slides.length > 1) {
                this.startAutoPlay();
            }
            
            this.track?.addEventListener('mouseenter', () => this.stopAutoPlay());
            this.track?.addEventListener('mouseleave', () => this.startAutoPlay());
            
            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') this.prev();
                if (e.key === 'ArrowRight') this.next();
            });
        },
        
        goTo(index) {
            this.current = index;
            this.updateTrack();
        },
        
        next() {
            this.current = (this.current + 1) % this.slides.length;
            this.updateTrack();
        },
        
        prev() {
            this.current = (this.current - 1 + this.slides.length) % this.slides.length;
            this.updateTrack();
        },
        
        updateTrack() {
            if (this.track) {
                this.track.style.transform = `translateX(-${this.current * 100}%)`;
            }
        },
        
        startAutoPlay() {
            this.stopAutoPlay();
            this.timer = setInterval(() => this.next(), interval);
        },
        
        stopAutoPlay() {
            clearInterval(this.timer);
        }
    }));

    // Lightbox
    Alpine.data('lightbox', () => ({
        open: false,
        currentSrc: '',
        currentAlt: '',
        sources: [],
        currentIndex: 0,
        
        init() {
            this.sources = Array.from(document.querySelectorAll('[data-lightbox]')).map(el => ({
                src: el.dataset.lightbox,
                alt: el.dataset.lightboxAlt || ''
            }));
            
            document.querySelectorAll('[data-lightbox]').forEach((el, index) => {
                el.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.openAt(index);
                });
            });
            
            document.addEventListener('keydown', (e) => {
                if (!this.open) return;
                if (e.key === 'Escape') this.close();
                if (e.key === 'ArrowLeft') this.prev();
                if (e.key === 'ArrowRight') this.next();
            });
        },
        
        openAt(index) {
            this.currentIndex = index;
            this.currentSrc = this.sources[index].src;
            this.currentAlt = this.sources[index].alt;
            this.open = true;
            document.body.style.overflow = 'hidden';
        },
        
        close() {
            this.open = false;
            document.body.style.overflow = '';
        },
        
        next() {
            this.currentIndex = (this.currentIndex + 1) % this.sources.length;
            this.currentSrc = this.sources[this.currentIndex].src;
            this.currentAlt = this.sources[this.currentIndex].alt;
        },
        
        prev() {
            this.currentIndex = (this.currentIndex - 1 + this.sources.length) % this.sources.length;
            this.currentSrc = this.sources[this.currentIndex].src;
            this.currentAlt = this.sources[this.currentIndex].alt;
        }
    }));

    // Smooth Scroll Anchor Links
    Alpine.data('smoothScroll', () => ({
        init() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', (e) => {
                    const targetId = anchor.getAttribute('href');
                    if (targetId === '#') return;
                    
                    const target = document.querySelector(targetId);
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        history.pushState(null, '', targetId);
                    }
                });
            });
        }
    }));
}

registerAlpineComponents();
Alpine.start();

// Utility Functions
window.Winterfell = {
    // Format currency
    formatCurrency(amount, currency = 'USD') {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currency,
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    },
    
    // Format date
    formatDate(date, options = {}) {
        const defaultOptions = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        return new Intl.DateTimeFormat('en-US', { ...defaultOptions, ...options }).format(new Date(date));
    },
    
    // Debounce
    debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    },
    
    // Throttle
    throttle(func, limit) {
        let inThrottle;
        return function(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    },
    
    // Get query param
    getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    },
    
    // Set query param without reload
    setQueryParam(param, value) {
        const url = new URL(window.location);
        if (value) {
            url.searchParams.set(param, value);
        } else {
            url.searchParams.delete(param);
        }
        window.history.replaceState({}, '', url);
    }
};

// Export for module usage
export default window.Winterfell;