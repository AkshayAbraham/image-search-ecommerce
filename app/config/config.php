<?php
// Application Configuration

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', BASE_PATH . '/public');
}

// Load environment variables
require_once BASE_PATH . '/app/core/Env.php';
Env::load();

// Application Constants from .env with fallbacks
define('APP_NAME', Env::get('APP_NAME', 'Image Search E-commerce'));
define('BASE_URL', Env::get('BASE_URL', 'http://localhost/image-search-ecommerce'));
define('UPLOAD_PATH', PUBLIC_PATH . '/assets/uploads/');
define('MAX_FILE_SIZE', (int) Env::get('MAX_FILE_SIZE', 2 * 1024 * 1024)); // 2MB
define('ALLOWED_TYPES', ['jpg', 'jpeg', 'png', 'gif']);

// Groq API Configuration from .env
define('GROQ_API_KEY', Env::get('GROQ_API_KEY'));
define('GROQ_API_URL', Env::get('GROQ_API_URL', 'https://api.groq.com/openai/v1/chat/completions'));
define('GROQ_MODEL', Env::get('GROQ_MODEL', 'llama-3.2-90b-vision-preview'));

// Validate required Groq configuration
if (empty(GROQ_API_KEY) || GROQ_API_KEY === 'your_actual_groq_api_key_here') {
    die("❌ Error: GROQ_API_KEY not properly configured in .env file");
}

// Create required directories
$directories = [
    UPLOAD_PATH,
    BASE_PATH . '/views/products',
    BASE_PATH . '/views/layouts', 
    BASE_PATH . '/public/assets/css',
    BASE_PATH . '/public/assets/js',
    BASE_PATH . '/public/assets/uploads'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);