from flask import Flask, request, render_template, redirect, url_for
import cv2
import numpy as np
import pytesseract
import os

# Initialize Flask app
app = Flask(__name__)
UPLOAD_FOLDER = 'uploads'
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

# Ensure upload folder exists
if not os.path.exists(UPLOAD_FOLDER):
    os.makedirs(UPLOAD_FOLDER)

# If using Windows, specify the Tesseract path (uncomment and update the path if necessary)
# pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'

def detect_license_plate(image_path):
    # Load the image
    image = cv2.imread(image_path)
    if image is None:
        return "Error loading image"

    # Convert the image to grayscale
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)

    # Apply edge detection
    edged = cv2.Canny(gray, 50, 200)

    # Find contours in the edged image
    contours, _ = cv2.findContours(edged, cv2.RETR_TREE, cv2.CHAIN_APPROX_SIMPLE)
    contours = sorted(contours, key=cv2.contourArea, reverse=True)[:10]

    license_plate = None
    for contour in contours:
        # Approximate the contour
        epsilon = 0.018 * cv2.arcLength(contour, True)
        approx = cv2.approxPolyDP(contour, epsilon, True)

        # If the contour has four sides, it may be a license plate
        if len(approx) == 4:
            x, y, w, h = cv2.boundingRect(approx)
            license_plate = gray[y:y + h, x:x + w]
            break

    if license_plate is None:
        return "License plate not detected"

    # Preprocess the license plate for OCR
    license_plate = cv2.resize(license_plate, None, fx=2, fy=2, interpolation=cv2.INTER_CUBIC)
    _, license_plate = cv2.threshold(license_plate, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)

    # Extract text using Tesseract
    text = pytesseract.image_to_string(license_plate, config='--psm 8')
    return text.strip()

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/upload', methods=['POST'])
def upload_file():
    if 'file' not in request.files:
        return "No file part"
    file = request.files['file']
    if file.filename == '':
        return "No selected file"
    if file:
        file_path = os.path.join(app.config['UPLOAD_FOLDER'], file.filename)
        file.save(file_path)
        license_number = detect_license_plate(file_path)
        return f"Detected License Plate Number: {license_number}"

if __name__ == '__main__':
    app.run(debug=True)
