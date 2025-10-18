/**
 * Database Schema Definition for E-commerce Product Catalog
 * 
 * Creates product table structure and populates with sample fashion items
 * Designed for image-based visual search functionality with AI feature storage
 * Uses SQLite database with optimized schema for product discovery
 * 
 * @package Database
 * @author Akshay
 * @version 1.0
 * 
 * @feature AI-Powered Search: Stores image analysis features in JSON format
 * @performance Optimized for text-based pattern matching and relevance scoring
 * @security Input validation handled at application layer
 */

-- =============================================================================
-- PRODUCTS TABLE DEFINITION
-- =============================================================================

/**
 * Products Table
 * 
 * Core table storing fashion product catalog with visual search capabilities
 * Includes structured product data and AI-extracted image features in JSON format
 * Supports both traditional text search and AI-powered visual similarity search
 * 
 * @indexes Automatic primary key indexing, consider adding indexes on:
 *          - image_features for JSON pattern matching
 *          - price for filtering
 *          - created_at for sorting
 */
CREATE TABLE IF NOT EXISTS products (
    -- Primary identifier with auto-increment for unique product IDs
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    
    -- Product display name for customer-facing interfaces
    name VARCHAR(255) NOT NULL,
    
    -- Detailed product description for SEO and customer information
    description TEXT,
    
    -- Retail price with precision for currency calculations
    -- DECIMAL(10,2) supports prices up to 99,999,999.99
    price DECIMAL(10,2) NOT NULL,
    
    -- High-quality product image URL for display and AI analysis
    -- Supports CDN URLs and local asset paths
    image_url VARCHAR(500) NOT NULL,
    
    /**
     * AI-Extracted Image Features (JSON Format)
     * 
     * Stores computer vision analysis results for visual search functionality
     * Enables pattern matching and similarity scoring without reprocessing
     * 
     * @structure {
     *   "colors": ["primary_color", "secondary_color"],
     *   "patterns": ["dominant_pattern"],
     *   "category": "product_category"
     * }
     * 
     * @usage Used in Product::searchByFeatures() for weighted relevance matching
     * @optimization Consider full-text search index for large catalogs
     */
    image_features TEXT,
    
    -- Automatic timestamp for product creation and sorting
    -- DEFAULT CURRENT_TIMESTAMP ensures accurate audit trail
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- =============================================================================
-- DATA INITIALIZATION
-- =============================================================================

/**
 * Clear Existing Products
 * 
 * Ensures clean slate for demo data population
 * In production, this would be replaced with incremental data loading
 * 
 * @warning This DELETE operation removes all existing products
 * @consideration For production: Use TRUNCATE or conditional INSERT
 */
DELETE FROM products;

/**
 * Sample Product Catalog
 * 
 * Populates database with realistic fashion products for demonstration
 * Each product includes high-quality images and pre-analyzed visual features
 * Features are stored in JSON format for AI-powered search functionality
 * 
 * @source Images sourced from Unsplash for demonstration purposes
 * @realism Products represent actual e-commerce inventory patterns
 * @coverage Includes various categories, patterns, and price points
 */
INSERT INTO products (name, description, price, image_url, image_features) VALUES
('Blue Striped T-Shirt', 'Comfortable cotton t-shirt with blue stripes', 24.99, 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=400', '{"colors": ["blue", "white"], "patterns": ["striped"], "category": "clothing"}'),
('Red Solid Hoodie', 'Warm red hoodie for casual wear', 45.99, 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=400', '{"colors": ["red"], "patterns": ["solid"], "category": "clothing"}'),
('Floral Summer Dress', 'Beautiful floral pattern dress for summer', 59.99, 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=400', '{"colors": ["yellow", "green"], "patterns": ["floral"], "category": "clothing"}'),
('Denim Jacket', 'Classic blue denim jacket', 79.99, 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=400', '{"colors": ["blue"], "patterns": ["denim"], "category": "clothing"}'),
('Black Running Shoes', 'Comfortable running shoes for sports', 89.99, 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400', '{"colors": ["black"], "patterns": ["solid"], "category": "footwear"}'),
('White Sneakers', 'Casual white sneakers for everyday wear', 65.99, 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=400', '{"colors": ["white"], "patterns": ["solid"], "category": "footwear"}'),
('Leather Crossbody Bag', 'Genuine leather crossbody bag in brown', 129.99, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400', '{"colors": ["brown"], "patterns": ["leather"], "category": "accessories"}'),
('Silver Pendant Necklace', 'Elegant silver necklace with crystal pendant', 45.50, 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=400', '{"colors": ["silver", "clear"], "patterns": ["metallic"], "category": "jewelry"}'),
('Wool Winter Scarf', 'Warm wool scarf in plaid pattern', 35.99, 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5b?w=400', '{"colors": ["red", "black", "white"], "patterns": ["plaid"], "category": "accessories"}'),
('Aviator Sunglasses', 'Classic aviator style sunglasses with UV protection', 89.99, 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400', '{"colors": ["gold", "black"], "patterns": ["metallic"], "category": "accessories"}'),
('Knit Sweater', 'Cozy knit sweater for winter season', 75.99, 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=400', '{"colors": ["cream"], "patterns": ["knit"], "category": "clothing"}'),
('Silk Evening Gown', 'Elegant silk gown for special occasions', 299.99, 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=400', '{"colors": ["navy"], "patterns": ["solid"], "category": "clothing"}'),
('Canvas Backpack', 'Durable canvas backpack for daily use', 55.99, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400', '{"colors": ["khaki"], "patterns": ["canvas"], "category": "accessories"}'),
('Pearl Earrings', 'Classic pearl stud earrings', 68.99, 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=400', '{"colors": ["white", "silver"], "patterns": ["pearl"], "category": "jewelry"}'),
('Linen Button-Down Shirt', 'Breathable linen shirt for summer', 49.99, 'https://images.unsplash.com/photo-1621072156002-e2fccdc0b176?w=400', '{"colors": ["white"], "patterns": ["solid"], "category": "clothing"}'),
('Wide Brim Sun Hat', 'Stylish wide brim hat for sun protection', 42.99, 'https://images.unsplash.com/photo-1521369909029-2afed882baee?w=400', '{"colors": ["beige"], "patterns": ["woven"], "category": "accessories"}'),
('Velvet Blazer', 'Luxurious velvet blazer for evening wear', 189.99, 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=400', '{"colors": ["burgundy"], "patterns": ["velvet"], "category": "clothing"}'),
('Gold Chain Bracelet', 'Delicate gold chain bracelet', 38.99, 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=400', '{"colors": ["gold"], "patterns": ["chain"], "category": "jewelry"}'),
('Cable Knit Beanie', 'Warm cable knit beanie in grey', 28.99, 'https://images.unsplash.com/photo-1576871337632-b9aef4c17ab9?w=400', '{"colors": ["grey"], "patterns": ["cable-knit"], "category": "accessories"}'),
('Pleated Midi Skirt', 'Elegant pleated midi skirt in black', 67.99, 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=400', '{"colors": ["black"], "patterns": ["pleated"], "category": "clothing"}'),
('Smart Watch', 'Modern smartwatch with fitness tracking', 199.99, 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=400', '{"colors": ["black", "silver"], "patterns": ["metallic"], "category": "accessories"}');