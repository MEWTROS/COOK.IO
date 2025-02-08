// script.js

const darkModeBtn = document.getElementById('dark-mode-btn');
const moonIcon = document.getElementById('moon-icon');
const header = document.querySelector('header');
const navLinks = document.querySelectorAll('.nav-link');
const logo = document.querySelector('.logo img'); // get the logo image element
const searchBar = document.getElementById('search-bar');
const searchBtn = document.querySelector('.search-btn'); // get the search button element
const mealBtns = document.querySelectorAll('.meal-btn');
const tabIndicator = document.querySelector('.tab-indicator');
const sparkleBtn = document.getElementById('sparkle-btn');
const imageContainer = document.getElementById('image-container');

// Toggle Dark Mode
darkModeBtn.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
    header.classList.toggle('dark-mode');
    navLinks.forEach(navLink => navLink.classList.toggle('dark-mode'));
    darkModeBtn.classList.toggle('active');

    // Change logo based on dark mode
    if (document.body.classList.contains('dark-mode')) {
        logo.src = './assets/images/logo-dark.png';
    } else {
        logo.src = './assets/images/logo-light.png';
    }
});

sparkleBtn.addEventListener('click', () => {
    alert('Sparkle button clicked!');
});

// Search functionality placeholder
searchBar.addEventListener('input', (e) => {
    const searchQuery = e.target.value.trim();
    console.log(`Search query: ${searchQuery}`);
});

searchBtn.addEventListener('click', () => {
    const searchQuery = searchBar.value.trim();
    console.log(`Searching for: ${searchQuery}`);
});

// Meal button functionality
mealBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
        // Remove selected class from all buttons
        mealBtns.forEach((btn) => btn.classList.remove('selected'));
        // Add selected class to the clicked button
        btn.classList.add('selected');

        // Move the tab indicator under the selected button
        const btnRect = btn.getBoundingClientRect();
        tabIndicator.style.left = `${btnRect.left}px`;
        tabIndicator.style.width = `${btnRect.width}px`;

        // Get the meal type from the clicked button
        const mealType = btn.getAttribute('data-tab');

        // Fetch the images from the PHP backend
        fetch(`getRecipes.php?meal_type=${mealType}`)
            .then(response => response.json())
            .then(data => {
                // Clear previous images
                imageContainer.innerHTML = '';

                // Create a new image row
                const imageRow = document.createElement('div');
                imageRow.classList.add('image-row');
                imageContainer.appendChild(imageRow);

                // Add the fetched images to the row
                data.forEach((image) => {
                    const imageContainerDiv = document.createElement('div');
                    imageContainerDiv.classList.add('image-container');
                    imageRow.appendChild(imageContainerDiv);

                    const imageElement = document.createElement('img');
                    imageElement.src = image.url;
                    imageContainerDiv.appendChild(imageElement);

                    const imageName = document.createElement('div');
                    imageName.classList.add('image-name');
                    imageName.textContent = image.name;
                    imageContainerDiv.appendChild(imageName);
                });

                // Show the image container
                imageContainer.classList.remove('hidden');
            })
            .catch(error => console.error('Error fetching images:', error));
    });
});







