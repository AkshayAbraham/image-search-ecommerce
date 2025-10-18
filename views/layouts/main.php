<?php
/**
 * Main Application Layout Template
 * 
 * Primary layout file that structures all page content
 * Implements consistent header/footer pattern with dynamic content injection
 * Manages flash messages, modals, and global JavaScript dependencies
 * 
 * @package Views/Layouts
 * @author Akshay
 * @version 1.0
 */
?>

<!-- Include Header Partial -->
<?php include BASE_PATH . '/views/partials/header.php'; ?>

    <!-- Flash Messages System -->
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <!-- Dynamic Alert Styling Based on Message Type -->
            <div class="bg-<?php echo $_SESSION['flash_message']['type'] === 'error' ? 'red' : 'green'; ?>-100 border border-<?php echo $_SESSION['flash_message']['type'] === 'error' ? 'red' : 'green'; ?>-400 text-<?php echo $_SESSION['flash_message']['type'] === 'error' ? 'red' : 'green'; ?>-700 px-4 py-3 rounded">
                <?php echo $_SESSION['flash_message']['message']; ?>
                <?php unset($_SESSION['flash_message']); ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content Area -->
    <main>
        <!-- Dynamic Content Injection -->
        <?php echo $content; ?>
    </main>

    <!-- Include Footer Partial -->
    <?php include BASE_PATH . '/views/partials/footer.php'; ?>

    <!-- Image Search Modal Component -->
    <div id="imageSearchModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <!-- Modal Container -->
        <div class="bg-white rounded-lg max-w-2xl w-full p-8">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-serif text-gray-900">SEARCH BY IMAGE</h3>
                <!-- Close Button -->
                <button onclick="closeImageSearch()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Image Search Form -->
            <form action="<?php echo BASE_URL; ?>/product/search" method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- Image Upload Area -->
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-gray-400 transition duration-200">
                    <!-- Hidden File Input -->
                    <input type="file" name="product_image" id="modal_product_image" 
                           class="hidden" accept="image/*" required onchange="previewImage(event)">
                    <!-- Upload Trigger Label -->
                    <label for="modal_product_image" class="cursor-pointer block">
                        <!-- Default Upload Icon State -->
                        <div id="uploadIcon" class="mb-4">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-12 1h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        
                        <!-- Image Preview State -->
                        <div id="imagePreview" class="hidden mb-4">
                            <img id="preview" class="mx-auto h-40 w-40 object-cover rounded border border-gray-200 shadow-sm">
                        </div>
                        
                        <!-- Upload Instructions -->
                        <span class="text-lg font-medium text-gray-700">Click to upload product image</span>
                        <p class="text-sm text-gray-500 mt-2">PNG, JPG, GIF up to 2MB</p>
                    </label>
                </div>
                
                <!-- File Name Display -->
                <div id="fileName" class="text-center text-sm text-gray-600 hidden"></div>
                
                <!-- Search Action Button -->
                <button type="submit" id="searchButton"
                        class="w-full bg-gray-900 text-white py-3 px-6 rounded hover:bg-gray-700 transition duration-200 font-semibold opacity-50 cursor-not-allowed"
                        disabled>
                    🔍 Search Similar Products
                </button>
                
                <!-- Clear Image Action -->
                <div class="text-center">
                    <button type="button" onclick="clearImage()" class="text-sm text-gray-500 hover:text-gray-700 hidden" id="clearButton">
                        Clear Image
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Application JavaScript -->
    <script src="/image-search-ecommerce/public/assets/js/app.js"></script>
</body>
</html>