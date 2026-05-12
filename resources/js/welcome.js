document.addEventListener('DOMContentLoaded', () => {
    const reveals = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.15 });

    reveals.forEach((el) => observer.observe(el));

    const nav = document.querySelector('nav');
    if (nav) {
        window.addEventListener('scroll', () => {
            nav.style.borderBottomColor = window.scrollY > 40
                ? 'rgba(255,255,255,0.1)'
                : 'rgba(255,255,255,0.07)';
        });
    }

    document.querySelectorAll('.menu-card-add').forEach((btn) => {
        btn.addEventListener('click', function () {
            this.textContent = '✓ تمت الإضافة';
            this.style.background = '#25d366';

            setTimeout(() => {
                this.textContent = '+ أضف';
                this.style.background = '';
            }, 1200);
        });
    });
});
