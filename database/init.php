<?php
/**
 * Database Initialization Script
 * 
 * One-time setup script for creating and populating the application database
 * Executes SQL schema file to create tables and load sample product data
 * Provides visual feedback for each execution step and verification
 * 
 * @package Database
 * @author Akshay
 * @version 1.0
 * 
 * @usage Run this script once during application setup
 * @security Ensure this script is removed or protected in production environments
 */

// Define base path for consistent file inclusion
define('BASE_PATH', dirname(__DIR__));

// Load application configuration and core dependencies
require_once BASE_PATH . '/app/config/config.php';
require_once BASE_PATH . '/app/core/Database.php';

echo "<h1>Initializing Database...</h1>";

/**
 * Main Database Initialization Process
 * 
 * Executes the following steps:
 * 1. Establishes database connection using Singleton pattern
 * 2. Reads and parses SQL schema file into executable statements
 * 3. Executes each SQL statement sequentially
 * 4. Verifies successful data insertion
 * 5. Provides comprehensive status reporting
 */
try {
    // Initialize database connection using Singleton pattern
    $db = Database::getInstance()->getConnection();
    
    /**
     * Schema Execution Phase
     * 
     * Reads the schema.sql file and executes all contained SQL statements
     * Handles both DDL (CREATE TABLE) and DML (INSERT) operations
     * Provides real-time feedback for each executed statement
     */
    $schema = file_get_contents(BASE_PATH . '/database/schema.sql');
    
    // Split SQL file into individual statements using semicolon delimiter
    $statements = array_filter(array_map('trim', explode(';', $schema)));
    
    // Execute each SQL statement with error handling
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $db->exec($statement);
            echo "<p>✓ Executed: " . substr($statement, 0, 50) . "...</p>";
        }
    }
    
    /**
     * Verification Phase
     * 
     * Confirms successful database initialization by:
     * - Counting inserted products to verify data population
     * - Checking for any execution errors
     * - Providing summary statistics
     */
    $count = $db->query("SELECT COUNT(*) as count FROM products")->fetch();
    
    // Success confirmation with visual indicators
    echo "<h2 style='color: green;'>✓ Database initialized successfully!</h2>";
    echo "<p>Total products loaded: <strong>{$count['count']}</strong></p>";
    
    /**
     * Navigation Instructions
     * 
     * Provides clear next steps for the user
     * Includes direct link to application homepage
     */
    echo "<p><a href='/image-search-ecommerce/public/'>Go to Homepage</a></p>";
    echo "<p><strong>Note:</strong> This script should be run only once during initial setup.</p>";
    
} catch (PDOException $e) {
    /**
     * Error Handling and Reporting
     * 
     * Catches and displays database-related exceptions
     * Provides clear error messages for troubleshooting
     * Uses visual indicators for error states
     */
    echo "<h2 style='color: red;'>Error initializing database: " . $e->getMessage() . "</h2>";
    echo "<p><strong>Troubleshooting Steps:</strong></p>";
    echo "<ul>";
    echo "<li>Check database file permissions</li>";
    echo "<li>Verify schema.sql file exists and is readable</li>";
    echo "<li>Ensure SQLite extension is enabled in PHP</li>";
    echo "<li>Check available disk space</li>";
    echo "</ul>";
    
    // Log detailed error for administrative purposes
    error_log("Database Initialization Error: " . $e->getMessage());
}
?>