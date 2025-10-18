<?php
/**
 * Products Index View Template
 * 
 * Main product catalog page displaying featured products in a responsive grid
 * Implements product cards with hover effects and call-to-action buttons
 * 
 * @package Views/Products
 * @author Akshay
 * @version 1.0
 */

// Start output buffering to capture rendered content
ob_start();
?>

<!-- Main Page Container -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 bg-[#E3E1DC]">
    
    <!-- ==========================================================================
         HERO SECTION
         ========================================================================== -->
    
    <!-- Brand Value Proposition -->
    <div class="text-center mb-16 pt-8">
        <!-- Brand Tagline -->
        <p class="text-xl text-black-600 font-medium">From duvet to the dancefloor, Brantree Edit has you covered</p>
    </div>

    <!-- ==========================================================================
         PRODUCT CATALOG SECTION
         ========================================================================== -->
    
    <!-- Featured Products Grid Container -->
    <div class="mt-8">
        
        <!-- Responsive Product Grid Layout -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <!-- Dynamic Product Loop -->
            <?php foreach ($products as $product): ?>
                
                <!-- ==================================================================
                     INDIVIDUAL PRODUCT CARD
                     ================================================================== -->
                
                <!-- Product Card Container with Hover Effects -->
                <div class="product-card bg-[#E3E1DC] rounded-none border border-gray-200 overflow-hidden shadow-sm hover:shadow-lg transition duration-300 ease-in-out">
                    
                    <!-- Product Image Container -->
                    <div class="overflow-hidden">
                        <!-- Product Image with Error Fallback -->
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                            alt="<?php echo htmlspecialchars($product['name']); ?>" 
                            class="product-image w-full h-80 object-cover border-b border-gray-200 transition duration-300 ease-in-out"
                            onerror="this.onerror=null; this.src='https://placehold.co/400x320/E8E8E8/444?text=Product+Image';">
                    </div>
                                
                    <!-- Product Information Section -->
                    <div class="p-5">
                        
                        <!-- Product Name -->
                        <h4 class="font-semibold text-lg mb-1 text-gray-900 tracking-wide">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </h4>
                        
                        <!-- Product Description with Line Clamping -->
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                            <?php echo htmlspecialchars($product['description']); ?>
                        </p>
                        
                        <!-- Price and Action Section -->
                        <div class="flex justify-between items-center pt-2">
                            <!-- Formatted Product Price -->
                            <span class="text-2xl text-gray-900 font-bold tracking-tight">
                                $<?php echo number_format($product['price'], 2); ?>
                            </span>
                            
                            <!-- Add to Cart Call-to-Action -->
                            <button class="bg-gray-900 text-white px-5 py-2 text-sm uppercase tracking-wider transition duration-300 hover:bg-gray-700">
                                Add to Bag
                            </button>
                        </div>
                    </div>
                </div>
                <!-- End Product Card -->
                
            <?php endforeach; ?>
            <!-- End Product Loop -->
            
        </div>
        <!-- End Product Grid -->
    </div>
    <!-- End Product Catalog Section -->

    <!-- ==========================================================================
         FEATURE PROMOTION SECTION
         ========================================================================== -->
    
    <!-- Image Search Feature Promotion -->
    <div class="mt-20 text-center">
        <p class="text-gray-600">Use the camera icon in the header to search for similar products by image</p>
    </div>
</div>
<!-- End Main Container -->

<?php
/**
 * Capture buffered content and load main layout template
 * 
 * @var string $content Contains all rendered HTML from this view
 * @uses BASE_PATH Global constant defining application root directory
 */
$content = ob_get_clean();
require_once BASE_PATH . '/views/layouts/main.php'; 
?>