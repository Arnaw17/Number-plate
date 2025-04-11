<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the image was uploaded
    if (isset($_FILES['vehicleImage']) && $_FILES['vehicleImage']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['vehicleImage']['tmp_name'];
        $fileName = $_FILES['vehicleImage']['name'];
        $fileType = $_FILES['vehicleImage']['type'];

        // Send image to Flask server
        $flaskUrl = 'http://127.0.0.1:5000/process-image'; // Flask endpoint
        $postData = [
            'vehicleImage' => new CURLFile($fileTmpPath, $fileType, $fileName)
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $flaskUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        } else {
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpCode === 200) {
                $data = json_decode($response, true);
                echo '<h2>Response from Flask:</h2>';
                echo '<pre>' . print_r($data, true) . '</pre>';
            } else {
                echo 'Error: Received HTTP code ' . $httpCode;
            }
        }
        curl_close($ch);
    } else {
        echo 'Error: No image uploaded or file upload error.';
    }
} else {
    echo 'Invalid request method.';
}
?>
