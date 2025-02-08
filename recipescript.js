document.addEventListener('DOMContentLoaded', () => {
    const darkModeBtn = document.getElementById('dark-mode-btn');
    const moonIcon = document.getElementById('moon-icon');
    const header = document.querySelector('header');
    const navLinks = document.querySelectorAll('.nav-link');
    const logo = document.querySelector('.logo img');
    const searchBtn = document.getElementById('search-btn');
    const searchBar = document.getElementById('search-bar');
    const mealBtns = document.querySelectorAll('.meal-btn');
    const tabIndicator = document.querySelector('.tab-indicator');
    const imageContainer = document.getElementById('image-container');

    // Function to fetch and display all recipes
    const fetchAllRecipes = async () => {
        imageContainer.innerHTML = ''; // Clear previous images

        try {
            const response = await fetch('searchrecipes.php'); // Adjust your endpoint for fetching all recipes
            const images = await response.json(); // Parse JSON response

            if (images.length === 0) {
                const noRecipesMessage = document.createElement('div');
                noRecipesMessage.textContent = "No recipes found.";
                imageContainer.appendChild(noRecipesMessage);
            } else {
                createImageRow(images);
            }

            imageContainer.classList.remove('hidden');
        } catch (error) {
            console.error('Error fetching images:', error);
            const errorMessage = document.createElement('div');
            errorMessage.textContent = "An error occurred while fetching recipes.";
            imageContainer.appendChild(errorMessage);
        }
    };

    // Function to perform search for recipes
    const performSearch = async (searchQuery) => {
        imageContainer.innerHTML = ''; // Clear previous images

        try {
            const response = await fetch(`searchrecipes.php?query=${encodeURIComponent(searchQuery)}`);
            const images = await response.json(); // Parse JSON response

            if (images.length === 0) {
                const noRecipesMessage = document.createElement('div');
                noRecipesMessage.textContent = "No recipes found for this search.";
                imageContainer.appendChild(noRecipesMessage);
            } else {
                createImageRow(images);
            }

            imageContainer.classList.remove('hidden');
        } catch (error) {
            console.error('Error fetching images:', error);
            const errorMessage = document.createElement('div');
            errorMessage.textContent = "An error occurred while searching for recipes.";
            imageContainer.appendChild(errorMessage);
        }
    };

    // Check for query in URL and perform search if present
    const urlParams = new URLSearchParams(window.location.search);
    const query = urlParams.get('query');
    if (query) {
        searchBar.value = query; // Populate the search bar
        performSearch(query); // Perform the search if there is a query
    } else {
        fetchAllRecipes(); // Fetch all recipes if no query is present
    }

    // Dark mode functionality
    darkModeBtn.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        header.classList.toggle('dark-mode');
        navLinks.forEach(navLink => navLink.classList.toggle('dark-mode'));
        darkModeBtn.classList.toggle('active');

        // Change logo based on dark mode
        logo.src = document.body.classList.contains('dark-mode') 
            ? 'logo-dark.png'  // Use relative paths for the logo
            : 'logo-light.png';
    });

    // Search button event listener
    searchBtn.addEventListener('click', () => {
        const searchQuery = searchBar.value.trim();
        if (searchQuery) {
            performSearch(searchQuery);
        }
    });

    // Meal button click event listeners
    mealBtns.forEach((button) => {
        button.addEventListener('click', async () => {
            const mealType = button.getAttribute('data-tab');
            imageContainer.innerHTML = ''; // Clear previous images

            try {
                const response = await fetch(`getrecipes.php?meal_type=${mealType}`);
                const images = await response.json(); // Parse JSON response

                if (images.length === 0) {
                    const noRecipesMessage = document.createElement('div');
                    noRecipesMessage.textContent = "No recipes found for this meal type.";
                    imageContainer.appendChild(noRecipesMessage);
                } else {
                    createImageRow(images);
                }

                imageContainer.classList.remove('hidden');
            } catch (error) {
                console.error('Error fetching images:', error);
                const errorMessage = document.createElement('div');
                errorMessage.textContent = "An error occurred while fetching recipes.";
                imageContainer.appendChild(errorMessage);
            }
        });
    });

    // Function to create image rows
    function createImageRow(images) {
        const imageRow = document.createElement('div');
        imageRow.classList.add('image-row');
        imageContainer.appendChild(imageRow);

        images.forEach((image) => {
            const imageContainerDiv = document.createElement('div');
            imageContainerDiv.classList.add('image-container');

            const link = document.createElement('a');
            link.href = `recipeshow.php?id=${image.id}`;
            link.target = "_self";

            const imageElement = document.createElement('img');
            imageElement.src = image.url;
            imageElement.alt = image.name;
            imageElement.classList.add('clickable-image');

            link.appendChild(imageElement);

            const imageName = document.createElement('div');
            imageName.classList.add('image-name');
            imageName.textContent = image.name;

            link.appendChild(imageName);
            imageContainerDiv.appendChild(link);
            imageRow.appendChild(imageContainerDiv);
        });
    }

    // Event delegation for clicks on images
    imageContainer.addEventListener('click', (event) => {
        const link = event.target.closest('a');
        if (link) {
            window.location.href = link.href;
        }
    });

    // Tab indicator movement functionality
    mealBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            mealBtns.forEach((btn) => btn.classList.remove('selected'));
            btn.classList.add('selected');
            const btnRect = btn.getBoundingClientRect();
            tabIndicator.style.left = `${btnRect.left}px`;
            tabIndicator.style.width = `${btnRect.width}px`;
        });
    });
});
