<?php
// ===============================================
// URL SHORTENER CONFIGURATION
// ===============================================

// -----------------------------------------------
// 1. DATABASE SETTINGS (MySQL/MariaDB)
// -----------------------------------------------

// Define constants for database credentials
define('DB_HOST', 'localhost'); // Database host
define('DB_NAME', 'DatabaseName'); // Database name
define('DB_USER', 'root'); // Database username
define('DB_PASS', 'Password'); // Database password

// -----------------------------------------------
// 2. APPLICATION SETTINGS
// -----------------------------------------------

// Define the base URL for the generated short links.
// IMPORTANT: This should be the path to your project root (e.g., 'http://localhost/LinkShortner')
define('BASE_URL', 'http://localhost/LinkShortner');

// Define the desired length for the short codes 
define('SHORT_CODE_LENGTH', 6);


// -----------------------------------------------
// 3. ESTABLISH DATABASE CONNECTION
// -----------------------------------------------

try {
    // Data Source Name (DSN) string
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    
    // Create a new PDO instance
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    
    // Set PDO attributes for better security and error handling
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Display error message if connection fails
    die("Database Connection Failed: " . $e->getMessage());
}

// $pdo variable is now available for use in other PHP files
?>
