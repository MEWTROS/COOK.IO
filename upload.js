// Dark mode functionality
document.addEventListener('DOMContentLoaded', () => {
    const darkModeBtn = document.getElementById('dark-mode-btn');
    const header = document.querySelector('header');
    const navLinks = document.querySelectorAll('.nav-link');
    const logo = document.querySelector('.logo img');
    const uploadContainer = document.querySelector('.upload-container');

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



document.getElementById('submit-btn').addEventListener('click', async () => {
    const imageInput = document.getElementById('image-upload');
    const file = imageInput.files[0];

    

    // Check if a file has been uploaded
    if (!file) {
        alert('Please upload an image.');
        return;
    }

    // Create FormData object to send the file and prompt
    const formData = new FormData();
    formData.append('file', file);
    
    // New detailed input prompt
    const inputPrompt = `
    You are an expert nutritionist analyzing the food items in the image.
    Start by determining if the image contains food items. 
    If the image does not contain any food items, 
    clearly state "No food items detected in the image." 
    and do not provide any calorie information. 
    If food items are detected, 
    follow the format below:
    
    If no food items are detected:
    No food items detected in the image.
    
    If food items are detected:
    Meal Name: [Name of the meal] (This should be on a new line)
    
    Ingredients: (This should be on a new line)
    1. Ingredient A (for the recipe)
    2. Ingredient B (for the recipe)
    3. Ingredient C (for the recipe)

    ...(leave 2 lines of space here)
    
    Cooking Instructions: (This should be on a new line)
    Step 1: Description of step one (Each step should be on a new line)
    Step 2: Description of step two
    Step 3: Description of step three

    ...(leave 2 lines of space here)
    
    
    Macronutrient Split: (each on a new line)
    - Protein: Y%
    - Carbohydrates: Z%
    - Fat: W%

    ...(leave 2 lines of space here)
    
    Important Details: (This should be on a new line)
    Provide the important details in a list format, with each detail on a new line.
    
    Note: Always identify the meal name and provide the ingredients and instructions. 
    VERY IMPORTANT NOTE: Your response will be used in a website for personal use, 
    so whenever you go on the next line, MAKE SURE TO COMPULSORILY PUT <BR> TAG.
    Avoid using asterisks for emphasis or formatting; present the information clearly and concisely.
    Ensure each recipe instruction is on a new line, without any formatting symbols.
    `;
    

    
    
    formData.append('prompt', inputPrompt); // Add the new input prompt here

    try {
        // Fetch the API endpoint
        const response = await fetch('http://127.0.0.1:5000/analyze', {
            method: 'POST',
            body: formData
        });

        // Check if the response is OK (status in the range 200-299)
        if (!response.ok) {
            const errorMessage = await response.text(); // Get the error message from response
            throw new Error(`Network response was not ok: ${response.status} ${errorMessage}`);
        }

        // Parse the JSON response
        const data = await response.json();
        
        // Check if the response contains a valid 'response' field
        if (data.response) {
            displayRecipe(data.response); // Display the response text
        } else {
            throw new Error('Invalid response format: no response field found');
        }
    } catch (error) {
        console.error('Error:', error.message); // Log the error message
        alert('An error occurred: ' + error.message); // Show user-friendly error message
    }
});

// Function to display the recipe
function displayRecipe(recipe) {
    const recipeOutput = document.getElementById('recipe-output');
    recipeOutput.innerHTML = `<h3>Food Analysis</h3><p>${recipe}</p>`; // Display the response text
    recipeOutput.classList.remove('hidden'); // Make sure the output element is visible
}
