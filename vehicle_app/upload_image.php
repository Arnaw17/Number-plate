<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['file'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);

    // Upload file
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        // Call Python script
        $command = escapeshellcmd("python detect_plate.py " . escapeshellarg($target_file));
        $output = shell_exec($command);
        echo $output;
    } else {
        echo "File upload failed.";
    }
}
?>
