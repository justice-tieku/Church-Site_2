<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    require_once 'includes/db.php';
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed. Please ensure the database is set up correctly.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';

try {
    switch ($action) {
        case 'register':
            registerUser($pdo, $data);
            break;
        case 'login':
            loginUser($pdo, $data);
            break;
        case 'check':
            checkAuth($pdo);
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}

function registerUser($pdo, $data) {
    $name = trim($data['name'] ?? '');
    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        return;
    }

    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
        return;
    }

    // Check if user already exists
    $stmt = $pdo->prepare("SELECT id FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Email already registered']);
        return;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = $pdo->prepare("INSERT INTO admins (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$name, $email, $hashedPassword]);

    echo json_encode(['success' => true, 'message' => 'Registration successful']);
}

function loginUser($pdo, $data) {
    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Email and password are required']);
        return;
    }

    // Get user
    $stmt = $pdo->prepare("SELECT id, name, email, password FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
        return;
    }

    // Generate token (simple implementation - in production use JWT)
    $token = bin2hex(random_bytes(32));

    // Store token (in production, use proper session management)
    $stmt = $pdo->prepare("UPDATE admins SET token = ?, token_expires = DATE_ADD(NOW(), INTERVAL 24 HOUR) WHERE id = ?");
    $stmt->execute([$token, $user['id']]);

    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'token' => $token,
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        ]
    ]);
}

function checkAuth($pdo) {
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? '';

    if (empty($token)) {
        echo json_encode(['success' => false, 'message' => 'No token provided']);
        return;
    }

    $stmt = $pdo->prepare("SELECT id, name, email FROM admins WHERE token = ? AND token_expires > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Invalid or expired token']);
        return;
    }

    echo json_encode(['success' => true, 'user' => $user]);
}
?>