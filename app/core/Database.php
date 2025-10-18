<?php
/**
 * Database Singleton Class
 * 
 * Implements Singleton pattern to provide single database connection instance
 * Manages SQLite database connection with PDO (PHP Data Objects)
 * Handles database initialization, connection, and configuration
 * 
 * @package Core
 * @uses PDO
 * @author Akshay
 * @version 1.0
 */
class Database {
    
    /**
     * @var PDO $pdo PDO database connection instance
     */
    private $pdo;
    
    /**
     * @var Database|null $instance Singleton instance of Database class
     */
    private static $instance = null;

    /**
     * Private constructor for Singleton pattern
     * 
     * Initializes SQLite database connection with PDO
     * Creates database directory if it doesn't exist
     * Configures PDO error handling and fetch modes
     * Enables SQLite foreign key constraints
     * 
     * @throws PDOException If database connection fails
     * @access private
     */
    private function __construct() {
        try {
            // Define SQLite database file path
            $dbPath = BASE_PATH . "/database/database.sqlite";
            
            // Create database directory if it doesn't exist
            $dbDir = dirname($dbPath);
            if (!is_dir($dbDir)) {
                mkdir($dbDir, 0777, true);
            }
            
            // Initialize PDO connection with SQLite
            $this->pdo = new PDO(
                "sqlite:" . $dbPath,
                null,  // SQLite doesn't require username
                null,  // SQLite doesn't require password
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,      // Throw exceptions on errors
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC  // Return associative arrays by default
                ]
            );
            
            // Enable foreign key constraints for SQLite (disabled by default)
            $this->pdo->exec("PRAGMA foreign_keys = ON");
            
        } catch (PDOException $e) {
            // Log and terminate on connection failure
            error_log("Database connection error: " . $e->getMessage());
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Get Singleton instance of Database class
     * 
     * Implements lazy initialization - creates instance only when first requested
     * Ensures single database connection throughout application lifecycle
     * 
     * @return Database Singleton instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Get PDO database connection
     * 
     * Returns the active PDO connection instance for database operations
     * Used by models and other classes to perform database queries
     * 
     * @return PDO Active PDO connection object
     */
    public function getConnection() {
        return $this->pdo;
    }

    /**
     * Prevent cloning of Singleton instance
     * 
     * @return void
     * @codeCoverageIgnore
     */
    private function __clone() { }

    /**
     * Prevent unserializing of Singleton instance
     * 
     * @return void
     * @codeCoverageIgnore
     */
    public function __wakeup() { }
}