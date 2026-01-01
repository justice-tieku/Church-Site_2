<?php
header('Content-Type: application/json');
include '../includes/db.php';

$response = ['success' => false, 'message' => ''];

try {
    // Validate input
    if (empty($_POST['title']) || empty($_FILES['audio'])) {
        throw new Exception('All fields are required');
    }

    // Process file upload
    $targetDir = "../uploads/";
    $fileName = basename($_FILES["audio"]["name"]);
    $targetFile = $targetDir . $fileName;
    
    // Check if file is an audio file
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    if ($fileType !== 'mp3') {
        throw new Exception('Only MP3 files are allowed');
    }

    // Move uploaded file
    if (!move_uploaded_file($_FILES["audio"]["tmp_name"], $targetFile)) {
        throw new Exception('Error uploading file');
    }

    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO messages (title, speaker, date, description, filename) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['title'],
        $_POST['speaker'],
        $_POST['date'],
        $_POST['description'],
        $fileName
    ]);

    $response['success'] = true;
    $response['message'] = 'Message uploaded successfully';
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>