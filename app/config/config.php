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
// Windows compatibility: If .env doesn't exist but example.env does, use it for local dev
if (!file_exists(BASE_PATH . '/.env') && file_exists(BASE_PATH . '/example.env') && !$isRender) {
    echo "<!-- Development Note: Using example.env for local setup -->";
    Env::load(BASE_PATH . '/example.env');
}
// Detect Render environment for dynamic BASE_URL
$isRender = getenv('RENDER') === 'true';

// Application Constants from .env with fallbacks
define('APP_NAME', Env::get('APP_NAME', 'Image Search E-commerce'));

// Dynamic BASE_URL for Render vs local development
if ($isRender) {
    // Render provides RENDER_EXTERNAL_URL environment variable
    $renderUrl = getenv('RENDER_EXTERNAL_URL');
    define('BASE_URL', $renderUrl ?: 'https://your-project-name.onrender.com');
} else {
    define('BASE_URL', Env::get('BASE_URL', 'http://localhost/image-search-ecommerce'));
}

define('UPLOAD_PATH', PUBLIC_PATH . '/assets/uploads/');
define('MAX_FILE_SIZE', (int) Env::get('MAX_FILE_SIZE', 2 * 1024 * 1024)); // 2MB
define('ALLOWED_TYPES', ['jpg', 'jpeg', 'png', 'gif']);

// Groq API Configuration from .env
define('GROQ_API_KEY', Env::get('GROQ_API_KEY'));
define('GROQ_API_URL', Env::get('GROQ_API_URL', 'https://api.groq.com/openai/v1/chat/completions'));
define('GROQ_MODEL', Env::get('GROQ_MODEL', 'llama-3.2-90b-vision-preview'));

// Validate required Groq configuration with better error handling
if (empty(GROQ_API_KEY) || GROQ_API_KEY === 'your_actual_groq_api_key_here') {
    if ($isRender) {
        // On Render, check if it might be set via environment variable directly
        $directEnvKey = getenv('GROQ_API_KEY');
        if ($directEnvKey && $directEnvKey !== 'your_actual_groq_api_key_here') {
            // Update the constant if found in direct environment
            define('GROQ_API_KEY', $directEnvKey);
        } else {
            die("❌ Error: GROQ_API_KEY not properly configured in Render environment variables. Please add GROQ_API_KEY to your Render dashboard.");
        }
    } else {
        die("❌ Error: GROQ_API_KEY not properly configured in .env file. Please check your .env configuration.");
    }
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

// Ensure uploads directory is writable (important for Render)
if (is_dir(UPLOAD_PATH) && !is_writable(UPLOAD_PATH)) {
    chmod(UPLOAD_PATH, 0755);
}

// Error reporting - more conservative in production
if ($isRender || Env::get('APP_ENV') === 'production') {
    error_reporting(E_ALL);
    ini_set('display_errors', 0); // Don't show errors to users in production
    ini_set('log_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}