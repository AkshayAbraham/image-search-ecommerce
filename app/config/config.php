<?php
/**
 * Application Configuration File
 * * Defines global constants (paths, URLs, API keys) required for the application.
 * Handles environment-specific loading (Render platform vs. local development via .env).
 * Sets up required file structure and environment-based error reporting configuration.
 * * @package Config
 * @author Akshay
 * @version 1.0
 */

// ===============================================
// 1. PATH DEFINITIONS
// ===============================================

// Define the absolute root path of the application (one level up from the current directory)
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

// Define the public directory path
if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', BASE_PATH . '/public');
}


// ===============================================
// 2. ENVIRONMENT LOADING
// ===============================================

// Load environment class
// This class is responsible for parsing and retrieving values from .env files
require_once BASE_PATH . '/app/core/env.php';

// Detect Render environment for dynamic BASE_URL and env variable handling
$isRender = getenv('RENDER') === 'true';
// Expose environment detection as a constant for use across the application
define('IS_RENDER', $isRender);

// Only try to load .env file if we're NOT on Render (which uses platform env vars) AND the file exists
if (!$isRender && file_exists(BASE_PATH . '/.env')) {
    // Load primary .env file for local development
    Env::load();
} elseif (!$isRender && file_exists(BASE_PATH . '/example.env')) {
    // Fallback for Windows compatibility or if .env is missing (development only)
    echo "<!-- Development Note: Using example.env for local setup -->";
    Env::load(BASE_PATH . '/example.env');
}
// Note: On Render, the application relies exclusively on environment variables 
// set in the dashboard, which are automatically available via getenv().

// Fetch APP_ENV, using platform variable on Render or Env class locally
$appEnv = $isRender ? (getenv('APP_ENV') ?: 'production') : Env::get('APP_ENV', 'development');


// ===============================================
// 3. APPLICATION CONSTANTS
// ===============================================

// Define Application Name (using platform env var on Render, or Env class locally)
define('APP_NAME', $isRender ? (getenv('APP_NAME') ?: 'Image Search E-commerce') : Env::get('APP_NAME', 'Image Search E-commerce'));

/**
 * Dynamic BASE_URL calculation
 * Sets the base URL for asset linking and routing:
 * - Render: Uses RENDER_EXTERNAL_URL (or a default fallback)
 * - Local: Uses the value from .env (or a default localhost path)
 */
if ($isRender) {
    $renderUrl = getenv('RENDER_EXTERNAL_URL');
    define('BASE_URL', $renderUrl ?: 'https://your-project-name.onrender.com');
} else {
    define('BASE_URL', Env::get('BASE_URL', 'http://localhost/image-search-ecommerce'));
}

// File Upload Configuration
define('UPLOAD_PATH', PUBLIC_PATH . '/assets/uploads/');
// Max file size in bytes (2MB default)
define('MAX_FILE_SIZE', (int) ($isRender ? (getenv('MAX_FILE_SIZE') ?: 2 * 1024 * 1024) : Env::get('MAX_FILE_SIZE', 2 * 1024 * 1024))); 
// Allowed file extensions for uploads
define('ALLOWED_TYPES', ['jpg', 'jpeg', 'png', 'gif']);


// ===============================================
// 4. GROQ API CONFIGURATION
// ===============================================

// Groq API Key (read from environment variables)
define('GROQ_API_KEY', $isRender ? getenv('GROQ_API_KEY') : Env::get('GROQ_API_KEY'));
// Groq API Endpoint URL
define('GROQ_API_URL', $isRender ? (getenv('GROQ_API_URL') ?: 'https://api.groq.com/openai/v1/chat/completions') : Env::get('GROQ_API_URL', 'https://api.groq.com/openai/v1/chat/completions'));
// Groq Model to be used for vision tasks
define('GROQ_MODEL', $isRender ? (getenv('GROQ_MODEL') ?: 'llama-3.2-90b-vision-preview') : Env::get('GROQ_MODEL', 'llama-3.2-90b-vision-preview'));

// Validate required Groq configuration and halt if key is missing or default placeholder
if (empty(GROQ_API_KEY) || GROQ_API_KEY === 'your_actual_groq_api_key_here') {
    die("❌ Error: GROQ_API_KEY not properly configured. " . 
        ($isRender ? 
            "Please add GROQ_API_KEY to your Render dashboard environment variables." : 
            "Please check your .env configuration."));
}


// ===============================================
// 5. DIRECTORY SETUP & PERMISSIONS
// ===============================================

// List of critical directories that must exist
$directories = [
    UPLOAD_PATH,
    BASE_PATH . '/views/products',
    BASE_PATH . '/views/layouts',
    BASE_PATH . '/public/assets/css',
    BASE_PATH . '/public/assets/js',
    BASE_PATH . '/public/assets/uploads' // Redundant definition but ensures uploads folder creation
];

// Loop through and create directories if they do not exist
foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        // Create directory recursively with permissions (0777 for maximum compatibility)
        mkdir($dir, 0777, true);
    }
}

// Ensure the main uploads directory is writable (important for CI/CD platforms like Render)
if (is_dir(UPLOAD_PATH) && !is_writable(UPLOAD_PATH)) {
    // Set appropriate permissions for the web server to write to the directory
    chmod(UPLOAD_PATH, 0755);
}


// ===============================================
// 6. ERROR REPORTING CONFIGURATION
// ===============================================

/**
 * Configure error reporting based on environment (production vs. development)
 * Production settings (Render or APP_ENV=production):
 * - Report all errors (E_ALL)
 * - Do NOT display errors to the user (display_errors=0)
 * - LOG errors to file (log_errors=1)
 */
if ($isRender || $appEnv === 'production') {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
} else {
    // Development settings: Display all errors directly for easier debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
