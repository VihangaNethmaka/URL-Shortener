<?php 
// Includes config file
require_once 'config.php';

// Checks for errors from redirect
$errorMessage = '';
if (isset($_GET['error']) && $_GET['error'] === 'invalid') {
    // Sets custom error message
    $errorMessage = "Houston, we have a problem. This short link is off-the-grid! It’s either expired, invalid, or hiding under a rock in the internet wilderness.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative; 
        }
        
        .card {
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            border: none;
            overflow: hidden;
            width: 100%;
        }
        
        .card-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            text-align: center;
            padding: 2rem 1rem;
            border-bottom: none;
        }
        
        .card-body {
            padding: 2.5rem;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1.1rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 0 0.25rem rgba(106, 17, 203, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
        }
        
        .result-container {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            border-left: 5px solid #6a11cb;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease;
            max-height: 0; 
            overflow: hidden; 
            visibility: hidden; 
        }
        
        .result-container.show {
            opacity: 1;
            transform: translateY(0);
            max-height: 200px; 
            visibility: visible;
        }

        .short-url {
            font-size: 1.2rem;
            font-weight: 600;
            color: #6a11cb;
            word-break: break-all;
        }
        
        .copy-btn {
            background-color: #6a11cb;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 0.5rem 1rem;
            transition: all 0.3s;
        }
        
        .copy-btn:hover {
            background-color: #5a0db8;
        }
        
        .stats {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .footer {
            text-align: center;
            margin-top: 2rem;
            color: rgba(255, 255, 255, 0.7);
        }

        #dynamicAlert {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(-20px);
            width: 90%;
            max-width: 500px;
            z-index: 1050;
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            border: none;
            border-radius: 15px;
            padding: 1.25rem 1.5rem;
            backdrop-filter: blur(10px);
            background: rgba(220, 53, 69, 0.95);
            color: white;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        #dynamicAlert.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        #dynamicAlert.alert-success {
            background: rgba(40, 167, 69, 0.95);
        }
        #dynamicAlert.alert-info {
            background: rgba(23, 162, 184, 0.95);
        }
        #dynamicAlert.alert-danger {
            background: rgba(220, 53, 69, 0.95);
        }
        .alert-icon {
            font-size: 1.4rem;
            margin-top: 2px;
            flex-shrink: 0;
        }
        .alert-content {
            flex: 1;
        }
        .alert-title {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert-message {
            font-size: 0.95rem;
            line-height: 1.5;
            margin: 0;
        }
        .alert-close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0;
            margin-top: 2px;
            opacity: 0.8;
            transition: opacity 0.2s;
            flex-shrink: 0;
        }
        .alert-close-btn:hover {
            opacity: 1;
        }
        .alert-pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
        }
    </style>
</head>
<body>
    <div id="dynamicAlert" class="d-none">
        <div class="alert-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="alert-content">
            <div class="alert-title">
                <span id="alertTitle">Error</span>
            </div>
            <p id="alertMessage" class="alert-message"></p>
        </div>
        <button type="button" class="alert-close-btn">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h1 class="display-5 fw-bold mb-3">URL Shortener</h1>
                        <p class="lead mb-0">Transform long URLs into short, shareable links in seconds</p>
                    </div>
                    <div class="card-body">
                        <form id="urlForm">
                            <div class="mb-4">
                                <label for="longUrl" class="form-label fw-semibold">Enter your long URL</label>
                                <input type="text" class="form-control form-control-lg" id="longUrl" placeholder="https://example.com/very-long-url" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-link me-2"></i>Shorten URL
                                </button>
                            </div>
                        </form>
                        
                        <div id="resultContainer" class="result-container">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0">Your Short URL</h5>
                                <span class="stats"></span> </div>
                            <div class="d-flex align-items-center">
                                <span id="shortUrl" class="short-url me-3"></span>
                                <button id="copyBtn" class="copy-btn">
                                    <i class="fas fa-copy me-1"></i>Copy
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="footer">
                    <p>&copy; 2025 URL Shortener. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        const urlForm = document.getElementById('urlForm');
        const longUrlInput = document.getElementById('longUrl');
        const resultContainer = document.getElementById('resultContainer');
        const shortUrlDisplay = document.getElementById('shortUrl');
        const submitBtn = urlForm.querySelector('button[type="submit"]');
        const statusMessageSpan = resultContainer.querySelector('.stats');

        const dynamicAlert = document.getElementById('dynamicAlert');
        const alertMessageSpan = document.getElementById('alertMessage');
        const alertTitleSpan = document.getElementById('alertTitle');
        const alertCloseBtn = dynamicAlert.querySelector('.alert-close-btn');

        function showAlert(message, type = 'danger') {
            const icons = {
                danger: 'exclamation-triangle',
                success: 'check-circle',
                info: 'info-circle'
            };
            const titles = {
                danger: 'Error',
                success: 'Success',
                info: 'Info'
            };
            
            dynamicAlert.classList.remove('alert-danger', 'alert-success', 'alert-info', 'd-none', 'alert-pulse');
            dynamicAlert.classList.add(`alert-${type}`, 'show');
            
            if (type === 'danger') {
                dynamicAlert.classList.add('alert-pulse');
            }
            
            dynamicAlert.querySelector('.alert-icon i').className = `fas fa-${icons[type]}`;
            alertTitleSpan.textContent = titles[type];
            alertMessageSpan.textContent = message;

            if (type !== 'danger') {
                setTimeout(() => {
                    hideAlert();
                }, 5000);
            }
        }

        function hideAlert() {
            dynamicAlert.classList.remove('show', 'alert-pulse');
            setTimeout(() => {
                dynamicAlert.classList.add('d-none');
            }, 400);
        }

        alertCloseBtn.addEventListener('click', hideAlert);

        <?php if (!empty($errorMessage)): ?>
            // Renders PHP error via JS
            showAlert(<?php echo json_encode($errorMessage); ?>, 'danger');
        <?php endif; ?>

        function setButtonState(isLoading) {
            const originalText = '<i class="fas fa-link me-2"></i>Shorten URL';
            if (isLoading) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Shortening...';
                submitBtn.disabled = true;
                hideResultContainer();
                hideAlert();
            } else {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }

        function hideResultContainer() {
            resultContainer.classList.remove('show');
        }

        function showResultContainer() {
            resultContainer.classList.add('show');
        }

        urlForm.addEventListener('submit', function(e) {
            e.preventDefault();
            setButtonState(true);

            const longUrl = longUrlInput.value;
            const formData = new FormData();
            formData.append('long_url', longUrl);

            // AJAX request to shorten.php
            fetch('shorten.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Checks for success or error response
                if (data.success) {
                    shortUrlDisplay.textContent = data.short_url;
                    statusMessageSpan.textContent = 'Link generated successfully!';
                    showResultContainer();
                    longUrlInput.value = '';
                } else {
                    showAlert(data.message, 'danger');
                    hideResultContainer();
                }
            })
            .catch(error => {
                // Handles network error
                console.error('Network Error:', error);
                showAlert('Looks like a connection hiccup! Please try again.', 'danger');
                hideResultContainer();
            })
            .finally(() => {
                // Resets button state
                setButtonState(false);
            });
        });

        document.getElementById('copyBtn').addEventListener('click', function() {
            const shortUrl = shortUrlDisplay.textContent;
            
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(shortUrl).then(() => {
                    showAlert('Copied to clipboard!', 'info');
                }).catch(err => {
                    console.error('Failed to copy to clipboard (Clipboard API):', err);
                    fallbackCopyToClipboard(shortUrl);
                });
            } else {
                fallbackCopyToClipboard(shortUrl);
            }

            function fallbackCopyToClipboard(text) {
                const tempInput = document.createElement('input');
                tempInput.value = text;
                document.body.appendChild(tempInput);
                tempInput.select();
                try {
                    document.execCommand('copy');
                    showAlert('Copied to clipboard!', 'info');
                } catch (err) {
                    console.error('Failed to copy to clipboard (execCommand):', err);
                    showAlert('Could not copy link automatically. Please copy it manually.', 'danger');
                }
                document.body.removeChild(tempInput);
            }
        });
    </script>
</body>
</html>
