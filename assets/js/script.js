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


    // Získanie modálneho okna a tlačidla
    const modal = document.getElementById("loginModal");
    const profileIcon = document.getElementById("profileIcon");
    const closeBtn = document.querySelector(".close");

    // Zobrazenie modálneho okna po kliknutí na ikonu používateľa
    profileIcon.addEventListener("click", () => {
        modal.style.display = "block";
    });

    // Skrytie modálneho okna po kliknutí na "X"
    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Skrytie modálneho okna po kliknutí mimo obsahu
    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    
