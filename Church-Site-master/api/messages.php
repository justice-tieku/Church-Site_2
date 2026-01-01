<?php
header('Content-Type: application/json');
include '../includes/db.php';

$stmt = $pdo->query("SELECT * FROM messages ORDER BY date DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($messages);
?>