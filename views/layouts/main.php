<?php
/**
 * Main Application Layout Template
 *
 * This file serves as the core HTML structure for the entire application.
 * It includes the header, navigation, dynamic content area ($content),
 * footer, and modal components. It handles dynamic asset loading based on the environment.
 *
 * Note: Assumes $title and $content variables are available via the controller/router.
 *
 * @package Views
 * @subpackage Layouts
 * @author [Your Name]
 * @version 1.0
 */

// Pick correct asset base depending on Render vs local development.
// This logic ensures asset paths work whether the application is hosted in a sub-folder (local)
// or directly at the root (Render/production environment).
$isRender = getenv('RENDER') === 'true';

// If running on Render, use root-relative public assets (public is the docroot).
// Otherwise (local dev with a subpath), use BASE_URL and include /public prefix.
$assetBase = $isRender ? '/assets' : rtrim(BASE_URL, '/') . '/public/assets';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Dynamic Page Title -->
    <title><?php echo $title ?? 'Image Search E-commerce'; ?></title>
    <!-- Tailwind CSS CDN for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Load custom application styles -->
    <link rel="stylesheet" href="<?php echo $assetBase; ?>/css/app.css">
</head>
<body class="bg-white">
    <!-- =============================================== -->
    <!-- HEADER AND NAVIGATION -->
    <!-- =============================================== -->
    <header class="bg-white border-b border-gray-200 z-10">
        
        <!-- Top Announcement Bar: Delivery and Customs Info -->
        <div class="bg-[#FFF] py-3 text-sm text-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-center space-x-16">
                <!-- Delivery Feature -->
                <span class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0v-6a1 1 0 011-1h2a1 1 0 011 1v6m-4 0h4"></path></svg>
                    Worldwide Tracked Delivery
                </span>
                <!-- Customs Feature -->
                <span class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.526a2 2 0 01-1.789-2.894l3.5-7zM7 9H4v5h3M4 14h3"></path></svg>
                    No customs charge UK & Ireland
                </span>
                <!-- Shipping Offer (Hidden on smaller screens) -->
                <span class="flex items-center hidden lg:flex">
                    <svg class="w-5 h-5 mr-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-4 4h.01M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Complimentary Shipping on orders over $299
                </span>
            </div>
        </div>

        <!-- Main Header: Logo, Search, Account, Cart -->
        <div class="bg-[#E3E1DC] py-1">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-24">
                
                <!-- Logo Area - Centered in this section -->
                <div class="flex-grow flex justify-center">
                    <div class="text-4xl font-serif font-black tracking-widest text-gray-900 leading-none text-center">
                        EDIT
                        <div class="text-base font-light tracking-widest text-gray-600 mt-2">
                            - BRANTREE EDIT -
                        </div>
                    </div>
                </div>

                <!-- Icons/Account Area (Right side) -->
                <div class="flex items-center space-x-8 text-gray-800">
                    
                    <!-- Expandable Search Bar/Image Search Trigger -->
                    <div class="flex items-center">
                        <!-- Search Input (Initially hidden, revealed by JS) -->
                        <div id="searchContainer" class="hidden flex items-center bg-white border border-gray-300 rounded-lg overflow-hidden transition-all duration-500 search-transition">
                            <input type="text" 
                                id="searchInput" 
                                placeholder="Search products..." 
                                class="px-4 py-2 w-64 focus:outline-none text-gray-700 rounded-l-lg">
                            <!-- Button to open the separate Image Search Modal -->
                            <button onclick="openImageSearch()" 
                                    class="camera-icon p-2 hover:bg-gray-100 transition duration-200 rounded-r-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-12 1h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Search Toggle Icon Button - Triggers the JavaScript function to show/hide the search bar -->
                        <button id="searchToggle" 
                                onclick="toggleSearch()" 
                                class="flex items-center hover:text-gray-900 transition duration-150 ml-2">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Visual Separator -->
                    <div class="h-8 w-px bg-black mx-6"></div>
                    
                    <!-- My Account Link -->
                    <a href="#" class="flex items-center hover:text-gray-900 transition duration-150 pl-0">
                        <svg class="w-7 h-7 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span class="text-xl">My Account</span>
                    </a>
                    
                    <!-- Visual Separator -->
                    <div class="h-8 w-px bg-black mx-6"></div>
                    
                    <!-- Shopping Cart Link with item count and total -->
                    <a href="#" class="flex items-center hover:text-gray-900 transition duration-150 pl-0">
                        <span class="text-xl mr-2 font-semibold">£0.00</span>
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <span class="ml-2 text-sm bg-gray-900 text-white w-6 h-6 flex items-center justify-center rounded-full">0</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigation Bar (Primary Menu) -->
        <nav class="border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-center space-x-14 h-20">
                    <!-- Navigation Items -->
                    <a href="/" class="text-xl font-medium text-gray-900 hover:text-gray-600 flex items-center border-b-2 border-gray-900 transition duration-150 py-2">Home</a>
                    <a href="#" class="text-xl font-medium text-gray-700 hover:text-gray-900 flex items-center border-b-2 border-transparent hover:border-gray-300 transition duration-150 py-2">Sleepwear</a>
                    <a href="#" class="text-xl font-medium text-gray-700 hover:text-gray-900 flex items-center border-b-2 border-transparent hover:border-gray-300 transition duration-150 py-2">Bags</a>
                    <a href="#" class="text-xl font-medium text-gray-700 hover:text-gray-900 flex items-center border-b-2 border-transparent hover:border-gray-300 transition duration-150 py-2">Jewellery</a>
                    <a href="#" class="text-xl font-medium text-gray-700 hover:text-gray-900 flex items-center border-b-2 border-transparent hover:border-gray-300 transition duration-150 py-2">Sunglasses</a>
                    <a href="#" class="text-xl font-medium text-gray-700 hover:text-gray-900 flex items-center border-b-2 border-transparent hover:border-gray-300 transition duration-150 py-2">Scarves</a>
                    <a href="#" class="text-xl font-medium text-gray-700 hover:text-gray-900 flex items-center border-b-2 border-transparent hover:border-gray-300 transition duration-150 py-2">Gifts</a>
                    <a href="#" class="text-xl font-medium text-gray-700 hover:text-gray-900 flex items-center border-b-2 border-transparent hover:border-gray-300 transition duration-150 py-2">Bridal</a>
                    <a href="#" class="text-xl font-medium text-gray-700 hover:text-gray-900 flex items-center border-b-2 border-transparent hover:border-gray-300 transition duration-150 py-2">Outlet</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- =============================================== -->
    <!-- FLASH MESSAGES -->
    <!-- =============================================== -->
    <!-- PHP logic to display session-based flash messages (success or error) -->
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-<?php echo $_SESSION['flash_message']['type'] === 'error' ? 'red' : 'green'; ?>-100 border border-<?php echo $_SESSION['flash_message']['type'] === 'error' ? 'red' : 'green'; ?>-400 text-<?php echo $_SESSION['flash_message']['type'] === 'error' ? 'red' : 'green'; ?>-700 px-4 py-3 rounded">
                <?php echo $_SESSION['flash_message']['message']; ?>
                <?php unset($_SESSION['flash_message']); // Clear the message after display ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- =============================================== -->
    <!-- MAIN CONTENT AREA -->
    <!-- =============================================== -->
    <!-- This variable holds the HTML generated by the current view/page -->
    <main>
        <?php echo $content; ?>
    </main>

    <!-- Include Footer Partial -->
    <?php include BASE_PATH . '/views/partials/footer.php'; ?>

    <!-- =============================================== -->
    <!-- IMAGE SEARCH MODAL -->
    <!-- =============================================== -->
    <!-- Full-screen modal for image-based product search, initially hidden -->
    <div id="imageSearchModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-2xl w-full p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-serif text-gray-900">SEARCH BY IMAGE</h3>
                <!-- Close button for the modal -->
                <button onclick="closeImageSearch()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Form for image upload and search -->
            <form action="<?php echo BASE_URL; ?>/product/search" method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- Image Upload Area with Preview -->
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-gray-400 transition duration-200">
                    <input type="file" name="product_image" id="modal_product_image" 
                               class="hidden" accept="image/*" required onchange="previewImage(event)">
                    <label for="modal_product_image" class="cursor-pointer block">
                        <!-- Default Upload Icon (Hidden when image is selected) -->
                        <div id="uploadIcon" class="mb-4">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-12 1h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        
                        <!-- Image Preview area (Hidden until image is selected) -->
                        <div id="imagePreview" class="hidden mb-4">
                            <img id="preview" class="mx-auto h-40 w-40 object-cover rounded border border-gray-200 shadow-sm">
                        </div>
                        
                        <span class="text-lg font-medium text-gray-700">Click to upload product image</span>
                        <p class="text-sm text-gray-500 mt-2">PNG, JPG, GIF up to 2MB</p>
                    </label>
                </div>
                
                <!-- File Name Display (Shown after successful upload) -->
                <div id="fileName" class="text-center text-sm text-gray-600 hidden"></div>
                
                <!-- Search Button (Initially disabled until an image is uploaded) -->
                <button type="submit" id="searchButton"
                        class="w-full bg-gray-900 text-white py-3 px-6 rounded hover:bg-gray-700 transition duration-200 font-semibold opacity-50 cursor-not-allowed"
                        disabled>
                    🔍 Search Similar Products
                </button>
                
                <div class="text-center">
                    <!-- Button to clear the selected image (Hidden until image is selected) -->
                    <button type="button" onclick="clearImage()" class="text-sm text-gray-500 hover:text-gray-700 hidden" id="clearButton">
                        Clear Image
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Application JavaScript -->
    <!-- Custom JS file that contains logic for the search toggle and image search modal -->
    <script src="<?php echo $assetBase; ?>/js/app.js"></script>
</body>
</html>
