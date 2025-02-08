document.addEventListener('DOMContentLoaded', () => {
    const darkModeBtn = document.getElementById('dark-mode-btn');
    const moonIcon = document.getElementById('moon-icon');
    const header = document.querySelector('header');
    const navLinks = document.querySelectorAll('.nav-link');
    const logo = document.querySelector('.logo img');
    const searchBar = document.getElementById('search-bar');
    const searchBtn = document.querySelector('.search-btn');
    const mealBtns = document.querySelectorAll('.meal-btn');
    const tabIndicator = document.querySelector('.tab-indicator');
    const imageContainer = document.getElementById('image-container');

   // Dark mode functionality
    darkModeBtn.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        header.classList.toggle('dark-mode');
        navLinks.forEach(navLink => navLink.classList.toggle('dark-mode'));
        darkModeBtn.classList.toggle('active');

        // Change logo based on dark mode
        logo.src = document.body.classList.contains('dark-mode') 
        ? './logo-dark.png'  // Use relative path based on your project structure
        : './logo-light.png';
    
    });
    // Search functionality
    searchBtn.addEventListener('click', () => {
        const searchQuery = searchBar.value.trim();
        if (searchQuery) {
            // Redirect to recipes.php with the search query as a parameter
            window.location.href = `recipes.php?query=${encodeURIComponent(searchQuery)}`;
        } else {
            console.log('Please enter a search term.');
        }
    });

    // Meal button click event listeners
    mealBtns.forEach((button) => {
        button.addEventListener('click', async () => {
            const mealType = button.getAttribute('data-tab'); // Use data attribute to get meal type
            imageContainer.innerHTML = ''; // Clear previous images

            try {
                // Fetch recipes based on the selected meal type
                const response = await fetch(`getrecipes.php?meal_type=${mealType}`);
                const images = await response.json(); // Parse JSON response
                console.log(images); // Log the fetched images for debugging

                // If no recipes found, show a message
                if (images.length === 0) {
                    const noRecipesMessage = document.createElement('div');
                    noRecipesMessage.textContent = "No recipes found for this meal type."; // Add a message for no recipes
                    imageContainer.appendChild(noRecipesMessage);
                } else {
                    // Create a row container to hold images
                    const imageRow = document.createElement('div');
                    imageRow.classList.add('image-row');
                    imageContainer.appendChild(imageRow);

                    // Iterate over each image from the response
                    images.forEach((image) => {
                        const imageContainerDiv = document.createElement('div');
                        imageContainerDiv.classList.add('image-container');

                        // Create a link that wraps around the image
                        const link = document.createElement('a');
                        link.href = `recipeshow.php?id=${image.id}`; // Link to the detailed recipe page
                        link.target = "_self"; // Open the recipe in the same tab

                        // Create the image element
                        const imageElement = document.createElement('img');
                        imageElement.src = image.url; // Use the image URL from the database
                        imageElement.alt = image.name; // Add alt text for accessibility
                        imageElement.classList.add('clickable-image'); // Optional class for styling

                        // Append the image element to the link
                        link.appendChild(imageElement);

                        // Create a div for the recipe name
                        const imageName = document.createElement('div');
                        imageName.classList.add('image-name');
                        imageName.textContent = image.name; // Display the name from the database

                        // Append the name below the image within the link
                        link.appendChild(imageName);

                        // Append the link (image + name) to the image container
                        imageContainerDiv.appendChild(link);
                        imageRow.appendChild(imageContainerDiv); // Append the container to the row
                    });
                }

                // Remove hidden class to display the image container
                imageContainer.classList.remove('hidden');
            } catch (error) {
                console.error('Error fetching images:', error); // Log errors to the console

                // Handle errors gracefully by showing an error message
                const errorMessage = document.createElement('div');
                errorMessage.textContent = "An error occurred while fetching recipes.";
                imageContainer.appendChild(errorMessage);
                imageContainer.classList.remove('hidden');
            }
        });
    });

    // Event delegation for clicks on images
    imageContainer.addEventListener('click', (event) => {
        const link = event.target.closest('a');
        if (link) {
            window.location.href = link.href; // Navigate to the link's URL
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
