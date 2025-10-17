<?php
// Include the configuration and database connection 
require_once 'config.php';

// Check if the short code is provided in the query string
// The .htaccess file rewrites the URL path (e.g., /abc123) to ?code=abc123
if (isset($_GET['code']) && !empty($_GET['code'])) {
    // Sanitize the short code input
    $shortCode = filter_var($_GET['code'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    try {
        // Prepare the SQL statement to find the long URL
        $stmt = $pdo->prepare("SELECT long_url FROM urls WHERE short_code = ?");
        $stmt->execute([$shortCode]);
        $result = $stmt->fetch();

        if ($result) {
            // Link found: Perform the redirection
            $longUrl = $result['long_url'];
            
            // Send the HTTP redirection header (301 Permanent or 302 Temporary)
            // Using 302 Temporary for better flexibility/analytics
            header("Location: " . $longUrl, true, 302);
            exit;
        }

    } catch (PDOException $e) {
        error_log("Database error in redirect.php: " . $e->getMessage());
        // Fallthrough to the error redirect below
    }
}

// If no code was provided, the link was invalid, or a database error occurred, 
// redirect to the index page with an error message.
header("Location: " . BASE_URL . "/index.php?error=invalid", true, 302);
exit;
?>

