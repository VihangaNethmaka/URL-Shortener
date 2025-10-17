<?php
// Include the configuration and database connection
require_once 'config.php';

header('Content-Type: application/json');

// Function to generate a random, unique short code
function generateUniqueCode(int $length, PDO $pdo): string {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charLength = strlen($characters);
    
    do {
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, $charLength - 1)];
        }
        
        // Check if the generated code already exists in the database
        $stmt = $pdo->prepare("SELECT short_code FROM urls WHERE short_code = ?");
        $stmt->execute([$code]);
        $exists = $stmt->fetch();

    } while ($exists);

    return $code;
}

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// Check for the long_url parameter
if (!isset($_POST['long_url']) || empty($_POST['long_url'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'URL is required.']);
    exit;
}

$longUrl = trim($_POST['long_url']);

// 1. Add "https://" if missing (User requirement)
if (!preg_match('#^https?://#i', $longUrl)) {
    $longUrl = 'https://' . $longUrl;
}

// Basic validation for a valid URL structure
if (!filter_var($longUrl, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid URL format.']);
    exit;
}

try {
    // 2. Check if the URL already exists to prevent duplicate entries
    $stmt = $pdo->prepare("SELECT short_code FROM urls WHERE long_url = ?");
    $stmt->execute([$longUrl]);
    $existing = $stmt->fetch();

    if ($existing) {
        // Return existing short code
        $shortCode = $existing['short_code'];
    } else {
        // 3. Generate a new unique short code
        $shortCode = generateUniqueCode(SHORT_CODE_LENGTH, $pdo);

        // 4. Insert the new URL and short code into the database
        $stmt = $pdo->prepare("INSERT INTO urls (short_code, long_url) VALUES (?, ?)");
        $stmt->execute([$shortCode, $longUrl]);
    }

    // 5. Return the full short URL
    $shortUrl = BASE_URL . '/' . $shortCode;

    echo json_encode(['success' => true, 'short_url' => $shortUrl]);

} catch (PDOException $e) {
    error_log("Database error in shorten.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error occurred during shortening.']);
}

?>
