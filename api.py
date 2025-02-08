from flask import Flask, request, jsonify
from flask_cors import CORS  # Import CORS
from dotenv import load_dotenv, find_dotenv
import os
import google.generativeai as genai
from PIL import Image
import io

# Load environment variables
load_dotenv(find_dotenv())

# Configure Google Generative AI library with an API key from environment variables
api_key = os.getenv("GOOGLE_API_KEY")
print(f"Using API key: {api_key}")  # Debugging log for API key
genai.configure(api_key=api_key)

app = Flask(__name__)
CORS(app)  # Enable CORS for all routes

def get_gemini_response(input_prompt, image):
    model = genai.GenerativeModel("gemini-1.5-pro-latest")

    # Check if image data is present
    if not image or not image[0].get('data'):
        raise ValueError("No valid image data provided")

    try:
        response = model.generate_content([input_prompt, image[0]])
        return response.text
    except Exception as e:
        print(f"Error generating response: {str(e)}")
        return "Error calling the Gemini API."

@app.route('/analyze', methods=['POST'])
def analyze_image():
    # Get the uploaded file and prompt from the request
    uploaded_file = request.files.get('file')
    input_prompt = request.form.get('prompt')

    # Debug logs
    if uploaded_file:
        print(f"Uploaded file: {uploaded_file.filename}, Content Type: {uploaded_file.content_type}")
    else:
        print("No file uploaded")

    if input_prompt:
        print(f"Input prompt: {input_prompt}")
    else:
        print("No prompt provided")

    # Prepare image data
    image_bytes = uploaded_file.read() if uploaded_file else None
    image = [{"mime_type": uploaded_file.content_type, "data": image_bytes}] if uploaded_file else []

    # Get response from Gemini API
    response_text = get_gemini_response(input_prompt, image)

    return jsonify({"response": response_text})

if __name__ == '__main__':
    app.run(debug=True)
