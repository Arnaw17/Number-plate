import cv2
import pytesseract
import requests

# If using Windows, specify the Tesseract path (update the path if needed)
# pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'

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
    url = "http://localhost/vehicle_search.php"  # Replace with your PHP script URL
    data = {'vehicleNumber': extracted_text}
    response = requests.post(url, data=data)
    print(response.text)

if __name__ == "__main__":
    image_path = "path_to_image.jpg"  # Replace with the path to the uploaded image
    extracted_text = detect_license_plate(image_path)
    print("Extracted Text:", extracted_text)
    send_to_php_server(extracted_text)
