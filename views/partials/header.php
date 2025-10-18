<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Tags and Document Configuration -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Dynamic Page Title with Fallback -->
    <title><?php echo $title ?? 'Image Search E-commerce'; ?></title>
    
    <!-- External Dependencies -->
    <!-- Tailwind CSS CDN for utility-first styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Custom Application Stylesheet -->
    <link href="/image-search-ecommerce/public/assets/css/app.css" rel="stylesheet">
</head>
<body class="bg-white">
    <!-- ==========================================================================
         MAIN SITE HEADER
         ========================================================================== -->
    
    <!-- Header Container with Sticky Positioning -->
    <header class="bg-white border-b border-gray-200 z-10">
        
        <!-- ======================================================================
             TOP PROMOTIONAL BAR
             ====================================================================== -->
        
        <!-- Service Features and Shipping Information -->
        <div class="bg-[#FFF] py-3 text-sm text-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-center space-x-16">
                <!-- Worldwide Delivery Feature -->
                <span class="flex items-center">
                    <!-- Delivery Icon -->
                    <svg class="w-5 h-5 mr-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0v-6a1 1 0 011-1h2a1 1 0 011 1v6m-4 0h4"></path>
                    </svg>
                    Worldwide Tracked Delivery
                </span>
                
                <!-- Customs Charge Information -->
                <span class="flex items-center">
                    <!-- Customs Icon -->
                    <svg class="w-5 h-5 mr-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.526a2 2 0 01-1.789-2.894l3.5-7zM7 9H4v5h3M4 14h3"></path>
                    </svg>
                    No customs charge UK & Ireland
                </span>
                
                <!-- Free Shipping Threshold (Hidden on Mobile) -->
                <span class="flex items-center hidden lg:flex">
                    <!-- Calendar/Shipping Icon -->
                    <svg class="w-5 h-5 mr-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-4 4h.01M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Complimentary Shipping on orders over $299
                </span>
            </div>
        </div>

        <!-- ======================================================================
             MAIN HEADER CONTENT
             ====================================================================== -->
        
        <!-- Brand Logo, Search, and User Actions -->
        <div class="bg-[#E3E1DC] py-1">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-24">
                
                <!-- ==================================================================
                     BRAND LOGO AREA
                     ================================================================== -->
                
                <!-- Centered Logo Container -->
                <div class="flex-grow flex justify-center">
                    <!-- Main Brand Logo -->
                    <div class="text-4xl font-serif font-black tracking-widest text-gray-900 leading-none text-center">
                        EDIT
                        <!-- Brand Subtitle -->
                        <div class="text-base font-light tracking-widest text-gray-600 mt-2">
                            - BRANTREE EDIT -
                        </div>
                    </div>
                </div>

                <!-- ==================================================================
                     USER INTERACTION AREA
                     ================================================================== -->
                
                <!-- Search, Account, and Cart Section -->
                <div class="flex items-center space-x-8 text-gray-800">
                    
                    <!-- Expandable Search Interface -->
                    <div class="flex items-center">
                        <!-- Search Input Container (Initially Hidden) -->
                        <div id="searchContainer" class="hidden flex items-center bg-white border border-gray-300 rounded-lg overflow-hidden transition-all duration-500 search-transition">
                            <!-- Text Search Input -->
                            <input type="text" 
                                id="searchInput" 
                                placeholder="Search products..." 
                                class="px-4 py-2 w-64 focus:outline-none text-gray-700 rounded-l-lg">
                            <!-- Image Search Trigger Button -->
                            <button onclick="openImageSearch()" 
                                    class="camera-icon p-2 hover:bg-gray-100 transition duration-200 rounded-r-lg">
                                <!-- Camera Icon for Visual Search -->
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-12 1h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Search Toggle Button -->
                        <button id="searchToggle" 
                                onclick="toggleSearch()" 
                                class="flex items-center hover:text-gray-900 transition duration-150 ml-2">
                            <!-- Search Icon -->
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Visual Separator -->
                    <div class="h-8 w-px bg-black mx-6"></div>
                    
                    <!-- User Account Access -->
                    <a href="#" class="flex items-center hover:text-gray-900 transition duration-150 pl-0">
                        <!-- User Account Icon -->
                        <svg class="w-7 h-7 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-xl">My Account</span>
                    </a>
                    
                    <!-- Visual Separator -->
                    <div class="h-8 w-px bg-black mx-6"></div>
                    
                    <!-- Shopping Cart with Price and Item Count -->
                    <a href="#" class="flex items-center hover:text-gray-900 transition duration-150 pl-0">
                        <!-- Cart Total -->
                        <span class="text-xl mr-2 font-semibold">£0.00</span>
                        <!-- Cart Icon -->
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <!-- Cart Item Count Badge -->
                        <span class="ml-2 text-sm bg-gray-900 text-white w-6 h-6 flex items-center justify-center rounded-full">0</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- ======================================================================
             PRIMARY NAVIGATION BAR
             ====================================================================== -->
        
        <!-- Main Site Navigation -->
        <nav class="border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Navigation Items Container -->
                <div class="flex justify-center space-x-14 h-20">
                    <!-- Home Navigation Link (Active State) -->
                    <a href="/image-search-ecommerce/public/" class="text-xl font-medium text-gray-900 hover:text-gray-600 flex items-center border-b-2 border-gray-900 transition duration-150 py-2">Home</a>
                    
                    <!-- Product Category Navigation Links -->
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