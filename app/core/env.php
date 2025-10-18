<?php
/**
 * Environment Configuration Class
 * 
 * Manages application environment variables with Singleton-like behavior
 * Parses .env files and provides access to configuration values
 * Supports fallback to $_ENV and $_SERVER superglobals for compatibility
 * Implements lazy loading and caching for optimal performance
 * 
 * @package Core
 * @author Akshay
 * @version 1.0
 */
class Env {
    
    /**
     * @var bool $loaded Flag indicating whether environment variables have been loaded
     */
    private static $loaded = false;
    
    /**
     * @var array $data Cache of parsed environment variables
     */
    private static $data = [];

    /**
     * Load environment variables from .env file
     * 
     * Parses .env file, extracts key-value pairs, and stores them in memory
     * Supports comments (lines starting with #) and quoted values
     * Also populates $_ENV and $_SERVER superglobals for framework compatibility
     * 
     * @param string|null $path Path to .env file (defaults to BASE_PATH/.env)
     * @return void
     * @throws Exception If .env file is not found
     */
    public static function load($path = null) {
        // Return early if already loaded (idempotent operation)
        if (self::$loaded) {
            return;
        }

        // Set default .env file path if not provided
        if ($path === null) {
            $path = BASE_PATH . '/.env';
        }

        // Validate .env file existence
        if (!file_exists($path)) {
            throw new Exception(".env file not found: {$path}");
        }

        // Read file lines, skipping empty lines and newline characters
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        // Process each line in .env file
        foreach ($lines as $line) {
            // Skip comment lines (starting with #)
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Parse key=value pairs
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Remove surrounding quotes while preserving quoted content
                $value = trim($value, '"\'');
                
                // Store in class cache
                self::$data[$key] = $value;
                
                // Populate superglobals for framework/library compatibility
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }

        // Mark as loaded to prevent redundant file reads
        self::$loaded = true;
    }

    /**
     * Get environment variable value
     * 
     * Retrieves value with fallback mechanism: class cache -> $_ENV -> $_SERVER -> default
     * Automatically loads .env file on first access (lazy loading)
     * 
     * @param string $key Environment variable name
     * @param mixed $default Default value if key is not found
     * @return mixed Environment variable value or default
     */
    public static function get($key, $default = null) {
        // Ensure environment variables are loaded
        self::load();
        
        // Return value with fallback hierarchy
        return self::$data[$key] ?? $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    }

    /**
     * Get all environment variables
     * 
     * Returns complete set of parsed environment variables
     * Useful for debugging, logging, or bulk operations
     * 
     * @return array Associative array of all environment variables
     */
    public static function all() {
        self::load();
        return self::$data;
    }

    /**
     * Check if environment variable exists
     * 
     * Verifies existence of environment variable in any available source
     * 
     * @param string $key Environment variable name to check
     * @return bool True if variable exists, false otherwise
     */
    public static function has($key) {
        self::load();
        return isset(self::$data[$key]) || isset($_ENV[$key]) || isset($_SERVER[$key]);
    }

    /**
     * Reset environment variables (primarily for testing)
     * 
     * Clears cached data and loaded state
     * WARNING: For testing purposes only, not for production use
     * 
     * @return void
     * @codeCoverageIgnore
     */
    public static function reset() {
        self::$loaded = false;
        self::$data = [];
    }
}