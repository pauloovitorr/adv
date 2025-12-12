
document.addEventListener('DOMContentLoaded', function() {

    // Slider de Depoimentos
    const testimonials = document.querySelectorAll('.testimonial-card');
    let currentTestimonial = 0;

    function showTestimonial(index) {
        testimonials.forEach((card, i) => {
            card.classList.remove('active');
            if (i === index) {
                card.classList.add('active');
            }
        });
    }

    function nextTestimonial() {
        currentTestimonial = (currentTestimonial + 1) % testimonials.length;
        showTestimonial(currentTestimonial);
    }

    if (testimonials.length > 0) {
        showTestimonial(0); // Mostra o primeiro depoimento
        setInterval(nextTestimonial, 6000); // Autoplay a cada 6 segundos
    }

    // Animação ao rolar a página (Scroll Animation)
    const scrollElements = document.querySelectorAll('.animate-on-scroll');

    const elementInView = (el, dividend = 1) => {
        const elementTop = el.getBoundingClientRect().top;
        return (
            elementTop <= (window.innerHeight || document.documentElement.clientHeight) / dividend
        );
    };

    const displayScrollElement = (element) => {
        element.classList.add('is-visible');
    };

    const hideScrollElement = (element) => {
        element.classList.remove('is-visible');
    }

    const handleScrollAnimation = () => {
        scrollElements.forEach((el) => {
            if (elementInView(el, 1.25)) {
                displayScrollElement(el);
            } 
        });
    }

    window.addEventListener('scroll', () => {
        handleScrollAnimation();
    });

    // Efeito de shrink no header ao rolar
    const header = document.querySelector('.header');
    if(header) {
        window.onscroll = function() {
            if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
                header.style.padding = '0.5rem 0';
            }
            else {
                header.style.padding = '1rem 0';
            }
        };
    }

    // // Validação de Formulário de Contato
    // const contactForm = document.getElementById('contact-form');
    // if(contactForm) {
    //     contactForm.addEventListener('submit', function(e) {
    //         e.preventDefault();

    //         const name = this.querySelector('input[name="name"]').value;
    //         const email = this.querySelector('input[name="email"]').value;
    //         const message = this.querySelector('textarea[name="message"]').value;

    //         if(name && email && message) {
    //             alert('Mensagem enviada com sucesso! Em breve entraremos em contato.');
    //             this.reset();
    //         } else {
    //             alert('Por favor, preencha todos os campos obrigatórios.');
    //         }
    //     });
    // }
});

