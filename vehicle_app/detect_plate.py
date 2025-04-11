import cv2
import pytesseract
import requests
import sys
import os

# If using Windows, specify the Tesseract path (uncomment and update the path if necessary)
pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'

def detect_license_plate(image_path):
    # Load the image
    image = cv2.imread(image_path)
    if image is None:
        return "Error loading image"

    # Convert to grayscale
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)

    # Edge detection
    edged = cv2.Canny(gray, 50, 200)

    # Find contours
    contours, _ = cv2.findContours(edged, cv2.RETR_TREE, cv2.CHAIN_APPROX_SIMPLE)
    contours = sorted(contours, key=cv2.contourArea, reverse=True)[:10]

    license_plate = None
    for contour in contours:
        epsilon = 0.018 * cv2.arcLength(contour, True)
        approx = cv2.approxPolyDP(contour, epsilon, True)
        if len(approx) == 4:
            x, y, w, h = cv2.boundingRect(approx)
            license_plate = gray[y:y + h, x:x + w]
            break

    if license_plate is None:
        return "License plate not detected"

    # Preprocess for OCR
    license_plate = cv2.resize(license_plate, None, fx=2, fy=2, interpolation=cv2.INTER_CUBIC)
    _, license_plate = cv2.threshold(license_plate, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)

    # OCR to extract text
    text = pytesseract.image_to_string(license_plate, config='--psm 8').strip()
    return text

def send_to_php_server(extracted_text):
    # URL of your PHP script to process the extracted text
    url = "http://localhost/vehicle_app/vehicle_search.php"  # Replace with the actual URL
    data = {'vehicleNumber': extracted_text}
    
    try:
        response = requests.post(url, data=data)
        return response.text
    except requests.exceptions.RequestException as e:
        return f"Error sending data to server: {e}"

if __name__ == "__main__":
    # Path to the uploaded image (should be passed as a command-line argument)
    if len(sys.argv) < 2:
        print("Usage: python detect_plate.py <image_path>")
        sys.exit(1)

    image_path = sys.argv[1]

    # Check if the file exists
    if not os.path.exists(image_path):
        print(f"Error: The file {image_path} does not exist.")
        sys.exit(1)

    # Detect license plate number from image
    extracted_text = detect_license_plate(image_path)
    print(f"Extracted License Plate Number: {extracted_text}")

    # Send the extracted license plate number to the PHP server
    response = send_to_php_server(extracted_text)
    print(f"Server Response: {response}")
