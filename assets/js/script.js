document.querySelectorAll('.filter-btn').forEach(button => {
        button.addEventListener('click', () => {
            const category = button.getAttribute('data-category');
            document.querySelectorAll('.event-card').forEach(card => {
                if (category === 'vsetky' || card.classList.contains(category)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
    document.addEventListener('DOMContentLoaded', () => {
        const carousel = document.querySelector('.events-carousel');
        const leftBtn = document.querySelector('.left-btn');
        const rightBtn = document.querySelector('.right-btn');
    
        // Posúvanie doľava
        leftBtn.addEventListener('click', () => {
            carousel.scrollBy({
                left: -300, // Posun o 300px doľava
                behavior: 'smooth'
            });
        });
    
        // Posúvanie doprava
        rightBtn.addEventListener('click', () => {
            carousel.scrollBy({
                left: 300, // Posun o 300px doprava
                behavior: 'smooth'
            });
        });
    });