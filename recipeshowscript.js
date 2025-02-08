// Dark mode functionality
document.addEventListener('DOMContentLoaded', () => {
    const darkModeBtn = document.getElementById('dark-mode-btn');
    const header = document.querySelector('header');
    const navLinks = document.querySelectorAll('.nav-link');
    const logo = document.querySelector('.logo img');
    const uploadContainer = document.querySelector('.container');

    darkModeBtn.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        header.classList.toggle('dark-mode');
        navLinks.forEach(navLink => navLink.classList.toggle('dark-mode'));
        uploadContainer.classList.toggle('dark-mode');
        darkModeBtn.classList.toggle('active');

        // Change logo based on dark mode
        logo.src = document.body.classList.contains('dark-mode')
            ? './logo-dark.png'  // Use the correct path for your dark mode logo
            : './logo-light.png';
    });
});

