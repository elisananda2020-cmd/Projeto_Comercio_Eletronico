document.addEventListener('DOMContentLoaded', () => {
    // ------------------------------------------------
    // 1. Lógica do Scroll Reveal
    // ------------------------------------------------
    
    // Seleciona todos os elementos com a classe 'reveal-item'
    const revealElements = document.querySelectorAll('.reveal-item');

    const observerOptions = {
        root: null, // Observa em relação à viewport
        rootMargin: '0px',
        threshold: 0.1 // Aparecerá quando 10% do elemento estiver na tela
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            // Se o elemento estiver na viewport
            if (entry.isIntersecting) {
                // Adiciona a classe que dispara a animação CSS
                entry.target.classList.add('is-visible');
                // Para de observar o elemento
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Começa a observar cada elemento de animação
    revealElements.forEach(element => {
        observer.observe(element);
    });

    // ------------------------------------------------
    // 2. Lógica do Carrossel (Ainda pendente - a ser adicionada)
    // ------------------------------------------------

    // Você precisará adicionar aqui a lógica para movimentar os carrosséis
    // quando os botões nav-btn (left-btn, right-btn) forem clicados.
});