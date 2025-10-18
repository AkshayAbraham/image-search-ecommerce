<?php
// Simple database reset script
define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/app/config/config.php';
require_once BASE_PATH . '/app/core/Database.php';

echo "<h1>Database Reset</h1>";

try {
    $db = Database::getInstance()->getConnection();
    
    // Read and execute the schema
    $schema = file_get_contents(BASE_PATH . '/database/schema.sql');
    $db->exec($schema);
    
    echo "<p style='color: green;'>✓ Database reset successfully!</p>";
    
    // Count products
    $count = $db->query("SELECT COUNT(*) as count FROM products")->fetch();
    echo "<p>Total products: <strong>{$count['count']}</strong></p>";
    
    echo "<p><a href='/image-search-ecommerce/public/'>Go to Homepage</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}