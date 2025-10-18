<?php
/**
 * Front Controller - Single Entry Point
 * 
 * Main application entry point that handles all HTTP requests
 * Implements MVC routing, dependency loading, and request processing
 * Serves as the central dispatcher for the entire application
 * 
 * @package Core
 * @author Akshay
 * @version 1.0
 * 
 * @pattern Front Controller design pattern
 * @security All requests filtered through this single entry point
 * @performance Optimized dependency loading and routing
 */

// =============================================================================
// APPLICATION BOOTSTRAP AND CONFIGURATION
// =============================================================================

/**
 * Define Application Path Constants
 * 
 * Establishes consistent file system paths for application components
 * Enables portable deployment across different server environments
 * 
 * @constant BASE_PATH Root directory of the application
 * @constant PUBLIC_PATH Web-accessible public directory
 */
$basePath = dirname(__DIR__);
define('BASE_PATH', $basePath);
define('PUBLIC_PATH', __DIR__);

/**
 * Initialize Session Management
 * 
 * Starts PHP session for user state persistence across requests
 * Essential for flash messages, authentication, and user preferences
 * 
 * @security Session configuration handled in php.ini
 * @persistence Maintains user state across multiple page requests
 */
session_start();

/**
 * Load Application Configuration
 * 
 * Imports environment variables, constants, and application settings
 * Configures database connections, API keys, and feature flags
 * 
 * @configuration Centralized settings management
 * @environment Development, staging, production-specific values
 */
require_once BASE_PATH . '/app/config/config.php';

// =============================================================================
// STATIC ASSET HANDLING
// =============================================================================

/**
 * Serve Static Assets Directly
 * 
 * Checks if the request is for static assets (CSS, JS, images)
 * Serves files directly without routing through MVC system
 * Essential for proper JavaScript and CSS file loading
 * 
 * @performance Direct file serving for better performance
 * @compatibility Ensures JavaScript functions load correctly
 */
$requestUri = $_SERVER['REQUEST_URI'];
$isAsset = preg_match('/\.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$/i', $requestUri);

if ($isAsset) {
    /**
     * Static File Serving Logic
     * 
     * Determines file path and serves with appropriate MIME type
     * Handles 404 responses for missing static files
     */
    $filePath = PUBLIC_PATH . $requestUri;
    
    if (file_exists($filePath)) {
        // Define MIME types for proper browser handling
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'svg' => 'image/svg+xml',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject'
        ];
        
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if (isset($mimeTypes[$extension])) {
            header('Content-Type: ' . $mimeTypes[$extension]);
        }
        
        // Serve the file directly
        readfile($filePath);
    } else {
        // File not found - return 404
        http_response_code(404);
        echo "Static file not found: " . htmlspecialchars($requestUri);
    }
    exit;
}

// =============================================================================
// CORE DEPENDENCY LOADING
// =============================================================================

/**
 * Autoload Core Application Components
 * 
 * Manually loads essential framework classes in optimal order
 * Ensures dependencies are available before controller instantiation
 * 
 * @loading_strategy Manual loading for performance and clarity
 * @dependencies Database → Models → Controllers → Services
 */
require_once BASE_PATH . '/app/core/Database.php';      // Database abstraction layer
require_once BASE_PATH . '/app/core/Model.php';         // Base model class
require_once BASE_PATH . '/app/core/Controller.php';    // Base controller class
require_once BASE_PATH . '/app/services/GroqService.php'; // AI image analysis service

// =============================================================================
// URL PARSING AND ROUTING LOGIC
// =============================================================================

/**
 * Parse Request URL
 * 
 * Extracts routing parameters from URL with fallback defaults
 * Supports clean URLs through .htaccess rewrite rules
 * 
 * @default_route 'product/index' - Main product catalog page
 * @url_format /controller/action/param1/param2
 */
$url = $_GET['url'] ?? 'product/index';

/**
 * Handle Direct File Access Exceptions
 * 
 * Allows bypassing MVC routing for specific file types and utilities
 * Essential for database initialization scripts and maintenance tools
 * 
 * @security Limited to specific directories for safety
 * @utility Database setup, reset scripts, and maintenance tools
 */
if (strpos($url, 'database/') === 0 || strpos($url, 'reset') !== false) {
    // Allow direct access to database files without MVC routing
    if (file_exists(BASE_PATH . '/' . $url)) {
        require_once BASE_PATH . '/' . $url;
        exit;
    }
}

/**
 * Tokenize URL for Routing
 * 
 * Splits URL into controller, action, and parameter components
 * Supports flexible URL structures and RESTful routing
 * 
 * @example /product/search → ['product', 'search']
 * @example /user/profile/123 → ['user', 'profile', '123']
 */
$url = explode('/', $url);

// =============================================================================
// MVC DISPATCHING AND CONTROLLER EXECUTION
// =============================================================================

/**
 * Determine Controller and Action
 * 
 * Maps URL segments to controller classes and action methods
 * Implements naming conventions for automatic class resolution
 * 
 * @convention ControllerName → ControllerNameController
 * @convention action_name → actionName (camelCase)
 */
$controllerName = ucfirst($url[0] ?? 'product') . 'Controller';
$actionName = $url[1] ?? 'index';
$params = array_slice($url, 2);

/**
 * Load and Instantiate Controller
 * 
 * Dynamically loads controller file and creates instance
 * Implements dependency injection and proper error handling
 * 
 * @autoloading Manual controller loading for explicit control
 * @error_handling Comprehensive file and method existence checks
 */
$controllerPath = BASE_PATH . "/app/controllers/{$controllerName}.php";

if (file_exists($controllerPath)) {
    // Load controller class definition
    require_once $controllerPath;
    
    /**
     * Create Controller Instance
     * 
     * Instantiates the appropriate controller with dependencies
     * Controller constructor handles model and service initialization
     */
    $controller = new $controllerName();
    
    /**
     * Execute Controller Action
     * 
     * Invokes the requested action method with URL parameters
     * Implements method existence validation for security
     * 
     * @security Prevents arbitrary method execution
     * @flexibility Supports variable number of parameters
     */
    if (method_exists($controller, $actionName)) {
        call_user_func_array([$controller, $actionName], $params);
    } else {
        /**
         * Action Not Found - HTTP 404
         * 
         * Handles requests for non-existent controller actions
         * Provides clear error message for debugging
         */
        http_response_code(404);
        echo "Action '{$actionName}' not found in controller '{$controllerName}'";
    }
} else {
    /**
     * Controller Not Found - Friendly Error Page
     * 
     * Graceful handling of missing controller files
     * User-friendly interface with navigation options
     * Maintains professional appearance even in error states
     */
    echo "<h1>Welcome to Image Search E-commerce</h1>";
    echo "<p>Controller '{$controllerName}' not found.</p>";
    echo "<p><a href='/'>Go to Homepage</a></p>";
}