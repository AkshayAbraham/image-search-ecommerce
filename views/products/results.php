<?php
/**
 * Search Results View Template
 * 
 * Displays AI-powered image search results with analysis data and product matches
 * Shows uploaded image, AI analysis breakdown, and visually similar products
 * 
 * @package Views/Products
 * @author Akshay
 * @version 2.0
 * 
 * @improvements Dynamic asset base, file existence checks, safe fallbacks
 * @robustness Handles missing files and data gracefully
 */

// Start output buffering to capture rendered content
ob_start();

// Compute asset base like layout does so this works on Render (public docroot) and local dev
$isRender = getenv('RENDER') === 'true';
$assetBase = $isRender ? '/assets' : rtrim(BASE_URL, '/') . '/public/assets';

// Determine uploaded image URL with server-side existence check
$searchImageFile = $searchImage ?? '';
$uploadsServerPath = PUBLIC_PATH . '/assets/uploads/' . $searchImageFile;
if ($searchImageFile && file_exists($uploadsServerPath)) {
    // Use rawurlencode for safe URLs if filename has spaces/special chars
    $searchImageUrl = $assetBase . '/uploads/' . rawurlencode($searchImageFile);
} else {
    // Fallback placeholder (avoids broken image icon)
    $searchImageUrl = 'https://placehold.co/240x240/E8E8E8/444?text=Search+Image';
}
?>

<!-- Main Results Container -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 bg-white">
    
    <!-- ==========================================================================
         RESULTS HEADER SECTION
         ========================================================================== -->
    
    <!-- Page Title and Navigation Actions -->
    <div class="text-center mb-12 pt-8">
        <!-- Main Results Heading -->
        <h2 class="text-5xl font-serif tracking-wider text-gray-900 mb-4 uppercase">SEARCH RESULTS</h2>
        <!-- Results Description -->
        <p class="text-xl text-gray-600 font-light">Similar products found based on your image</p>
        
        <!-- Action Buttons Container -->
        <div class="flex justify-center gap-4 mb-8">
            <!-- New Search Action -->
            <a href="<?php echo rtrim(BASE_URL, '/'); ?>/" 
               class="bg-gray-800 text-white px-8 py-3 uppercase tracking-wider font-semibold hover:bg-gray-700 transition duration-300 inline-block">
                🔄 New Search
            </a>
            <!-- Back to Catalog Action -->
            <a href="<?php echo rtrim(BASE_URL, '/'); ?>/" 
               class="bg-[#E3E1DC] text-gray-800 border border-gray-300 px-8 py-3 uppercase tracking-wider font-semibold hover:bg-gray-200 transition duration-300 inline-block">
                ← Back to All Products
            </a>
        </div>
    </div>

    <!-- ==========================================================================
         SEARCH ANALYSIS SECTION
         ========================================================================== -->
    
    <!-- Uploaded Image and AI Analysis Results -->
    <div class="bg-white border border-gray-200 p-8 mb-12 shadow-md">
        <!-- Responsive Flex Container -->
        <div class="flex flex-col md:flex-row items-center gap-8">
            
            <!-- Uploaded Image Display -->
            <div class="text-center">
                <h3 class="font-semibold text-lg mb-4 text-gray-900">YOUR SEARCH IMAGE</h3>
                <!-- User's Uploaded Image with Fallback -->
                <img src="<?php echo htmlspecialchars($searchImageUrl); ?>"
                     alt="Search image" class="h-60 w-60 object-cover border border-gray-200 shadow-sm"
                     onerror="this.onerror=null;this.src='https://placehold.co/240x240/E8E8E8/444?text=Search+Image'">
            </div>
            
            <!-- AI Analysis Results -->
            <div class="flex-1">
                <!-- Analysis Section Heading -->
                <h3 class="font-semibold text-lg mb-4 text-gray-900">ANALYSIS RESULTS</h3>

                <!-- Analysis Metrics Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    
                    <!-- Detected Pattern Metric -->
                    <div class="bg-gray-50 p-4 border border-gray-200">
                        <span class="font-medium text-gray-700">Detected Pattern:</span>
                        <!-- Pattern Badge -->
                        <span class="bg-gray-900 text-white px-3 py-1 text-sm ml-2 font-semibold uppercase rounded">
                            <?= htmlspecialchars($analysis['detected_pattern'] ?? 'N/A'); ?>
                        </span>
                    </div>
                    
                    <!-- Confidence Score Metric -->
                    <div class="bg-gray-50 p-4 border border-gray-200">
                        <span class="font-medium text-gray-700">Confidence Score:</span>
                        <!-- Confidence Percentage Badge -->
                        <span class="bg-green-600 text-white px-3 py-1 text-sm ml-2 font-semibold rounded">
                            <?= isset($analysis['confidence']) ? number_format($analysis['confidence'] * 100, 1) . '%' : 'N/A'; ?>
                        </span>
                    </div>
                    
                    <!-- Dominant Colors Metric -->
                    <div class="bg-gray-50 p-4 border border-gray-200">
                        <span class="font-medium text-gray-700">Dominant Colors:</span>
                        <!-- Color List Display -->
                        <span class="text-sm ml-2 font-semibold text-gray-800">
                            <?= htmlspecialchars(!empty($analysis['dominant_colors']) ? implode(', ', $analysis['dominant_colors']) : 'N/A'); ?>
                        </span>
                    </div>
                    
                    <!-- Product Category Metric -->
                    <div class="bg-gray-50 p-4 border border-gray-200">
                        <span class="font-medium text-gray-700">Category:</span>
                        <!-- Category Badge -->
                        <span class="text-sm ml-2 font-semibold text-gray-800 uppercase">
                            <?= htmlspecialchars($analysis['category'] ?? 'N/A'); ?>
                        </span>
                    </div>
                </div>

                <!-- ==================================================================
                     DEBUG PANEL - RAW AI RESPONSE
                     ================================================================== -->
                
                <!-- Collapsible Debug Information -->
                <?php if (!empty($analysis['raw_analysis'])): ?>
                    <details class="bg-gray-100 border border-gray-300 p-4 rounded mt-4">
                        <!-- Debug Panel Summary -->
                        <summary class="cursor-pointer font-semibold text-gray-700">
                            🧠 View Full Groq Response (Raw JSON)
                        </summary>
                        <!-- Formatted JSON Output -->
                        <pre class="mt-2 text-sm bg-white border border-gray-200 rounded p-3 overflow-x-auto text-gray-800">
<?= htmlspecialchars(json_encode($analysis['raw_analysis'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)); ?>
                        </pre>
                    </details>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ==========================================================================
         PRODUCT RESULTS SECTION
         ========================================================================== -->
    
    <!-- Matching Products Display -->
    <div class="mb-8">
        <!-- Results Count Heading -->
        <h3 class="text-3xl font-serif tracking-wide text-gray-900 text-center mb-10 border-b pb-4 border-gray-200 uppercase">
            MATCHING PRODUCTS (<?php echo count($products); ?> FOUND)
        </h3>
        
        <!-- Product Results Conditional Display -->
        <?php if (!empty($products)): ?>
            <!-- Product Grid Layout -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Product Loop -->
                <?php foreach ($products as $product): ?>
                    <!-- Individual Product Card -->
                    <div class="bg-white rounded-none border border-gray-100 overflow-hidden shadow-sm hover:shadow-lg transition duration-300 ease-in-out">
                        
                        <!-- Product Image with Error Fallback -->
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                             class="w-full h-80 object-cover border-b border-gray-100"
                             onerror="this.onerror=null; this.src='https://placehold.co/400x320/E8E8E8/444?text=Product+Image';">
                             
                        <!-- Product Information -->
                        <div class="p-5">
                            <!-- Product Name -->
                            <h4 class="font-semibold text-lg mb-1 text-gray-900 tracking-wide">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </h4>
                            
                            <!-- Product Description -->
                            <p class="text-gray-500 text-sm mb-4 line-clamp-2">
                                <?php echo htmlspecialchars($product['description']); ?>
                            </p>
                            
                            <!-- Price and Action Section -->
                            <div class="flex justify-between items-center pt-2">
                                <!-- Formatted Price -->
                                <span class="text-2xl text-gray-900 font-bold tracking-tight">
                                    $<?php echo number_format($product['price'], 2); ?>
                                </span>
                                
                                <!-- Add to Cart Action -->
                                <button class="bg-gray-900 text-white px-5 py-2 text-sm uppercase tracking-wider transition duration-300 hover:bg-gray-700">
                                    Add to Bag
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <!-- End Product Loop -->
                
            </div>
            <!-- End Product Grid -->
            
        <?php else: ?>
            <!-- ==================================================================
                 NO RESULTS STATE
                 ================================================================== -->
            
            <!-- Empty Results Message -->
            <div class="bg-white border border-gray-200 p-12 text-center">
                <!-- No Results Icon -->
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0L12 20l2.828-2.828a4 4 0 015.656 5.656l-8 8a4 4 0 01-5.656 0l-8-8a4 4 0 115.656-5.656L4 20l2.828-2.828z"></path>
                </svg>
                
                <!-- No Results Message -->
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No similar products found</h3>
                <p class="text-gray-500 mb-6">Try uploading a different image or check back later for more products.</p>
                
                <!-- Retry Action -->
                <a href="<?php echo rtrim(BASE_URL, '/'); ?>/" class="bg-gray-900 text-white px-8 py-3 uppercase tracking-wider font-semibold hover:bg-gray-700 transition duration-300 inline-block">
                    New Search
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
/**
 * Capture buffered content and load main layout template
 * 
 * @var string $content Contains all rendered HTML from results view
 * @uses BASE_PATH Global constant defining application root directory
 */
$content = ob_get_clean();
require_once BASE_PATH . '/views/layouts/main.php'; 
?>