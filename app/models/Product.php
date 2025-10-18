<?php
/**
 * Product Model Class
 * 
 * Handles product data operations using in-memory SQLite database
 * Implements image-based product search with relevance scoring
 * Loads schema and mock data from SQL file on initialization
 * 
 * @package Models
 * @author Akshay
 * @version 1.0
 */
class Product {
    
    /**
     * @var PDO $db In-memory SQLite database connection
     */
    private $db;

    /**
     * Constructor
     * 
     * Initializes in-memory SQLite database and loads schema
     * Sets PDO error mode to exceptions for robust error handling
     * Automatically creates database structure from schema.sql file
     * 
     * @throws Exception If schema file is not found or SQL execution fails
     */
    public function __construct() {
        // Initialize in-memory SQLite database (volatile, created fresh each time)
        $this->db = new PDO('sqlite::memory:');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Load database schema and populate with mock data
        $schemaFile = BASE_PATH . '/database/schema.sql';
        if (file_exists($schemaFile)) {
            $schemaSql = file_get_contents($schemaFile);
            $this->db->exec($schemaSql);
        } else {
            throw new Exception("Schema file not found at $schemaFile");
        }
    }

    /**
     * Get all products
     * 
     * Retrieves complete product catalog sorted by creation date (newest first)
     * Suitable for product listing pages and catalog displays
     * 
     * @return array Array of all products as associative arrays
     */
    public function getAllProducts() {
        $stmt = $this->db->query("SELECT * FROM products ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Search products by image analysis features
     * 
     * Performs similarity-based search using AI-analyzed image features
     * Implements weighted relevance scoring based on pattern, colors, and category
     * Uses SQL LIKE matching on JSON-formatted image_features text column
     * 
     * @param array $features Image analysis results from AI service containing:
     *               - detected_pattern: Primary pattern identified in image
     *               - dominant_colors: Array of dominant color names
     *               - category: Product category classification
     * @return array Matching products sorted by relevance score (highest first)
     * 
     * @algorithm
     * Relevance Score Formula:
     * - Pattern match: 40% weight (most important for visual similarity)
     * - Primary color match: 30% weight 
     * - Secondary color match: 20% weight
     * - Category match: 10% weight (broad classification)
     * 
     * @example
     * $features = [
     *     'detected_pattern' => 'floral',
     *     'dominant_colors' => ['pink', 'green'],
     *     'category' => 'clothing'
     * ];
     * $results = $productModel->searchByFeatures($features);
     */
    public function searchByFeatures($features) {
        // Normalize input features for case-insensitive matching
        $pattern = strtolower($features['detected_pattern'] ?? 'solid');
        $colors = array_map('strtolower', $features['dominant_colors'] ?? ['black', 'white']);
        $category = strtolower($features['category'] ?? 'clothing');

        /**
         * SQL Query with Weighted Relevance Scoring
         * 
         * Uses LIKE operator for flexible pattern matching in JSON text
         * Calculates relevance score using weighted sum of feature matches
         * Ensures at least one feature matches while prioritizing strong matches
         */
        $sql = "
            SELECT *,
                (
                    (image_features LIKE :pattern) * 0.4 +
                    (image_features LIKE :color1) * 0.3 +
                    (image_features LIKE :color2) * 0.2 +
                    (image_features LIKE :category) * 0.1
                ) AS relevance
            FROM products
            WHERE image_features LIKE :pattern
               OR image_features LIKE :color1
               OR image_features LIKE :color2
               OR image_features LIKE :category
            ORDER BY relevance DESC
            LIMIT 20;
        ";

        // Execute prepared statement with feature parameters
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':pattern', '%' . $pattern . '%');
        $stmt->bindValue(':color1', '%' . $colors[0] . '%');
        $stmt->bindValue(':color2', '%' . $colors[1] . '%');
        $stmt->bindValue(':category', '%' . $category . '%');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find product by ID
     * 
     * Retrieves single product using primary key lookup
     * 
     * @param int $id Product identifier
     * @return array|null Product data or null if not found
     */
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Get database connection (for testing and debugging)
     * 
     * @return PDO Active database connection instance
     * @internal
     */
    public function getConnection() {
        return $this->db;
    }
}