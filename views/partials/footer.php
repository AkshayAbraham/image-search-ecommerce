<!-- 
Footer Partial Component
Main website footer containing customer testimonials, navigation, and newsletter signup
Implements responsive design with grid layout and brand-consistent styling
-->

<!-- Footer Container -->
<footer class="bg-white">
    <!-- ==========================================================================
         CUSTOMER TESTIMONIALS SECTION
         ========================================================================== -->
    
    <!-- Testimonials Background Container -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Heading -->
            <h2 class="text-3xl font-serif text-center text-gray-900 mb-12 tracking-wider">Our Happy Customers</h2>
            
            <!-- Testimonials Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                
                <!-- Testimonial 1: Dublin Customer -->
                <div class="text-center">
                    <div class="bg-gray">
                        <!-- Customer Quote -->
                        <p class="text-gray-600 italic mb-4">"Ordered at 11am Monday arrived, 10am next day to Dublin, delighted with the speedy"</p>
                        <!-- Customer Name and Location -->
                        <p class="font-semibold text-gray-900">Sarah - Dublin</p>
                    </div>
                </div>
                
                <!-- Testimonial 2: Cork Customer -->
                <div class="text-center">
                    <div class="bg-gray">
                        <!-- Customer Quote -->
                        <p class="text-gray-600 italic mb-4">I totally love my sleepwear; it's so glam! I wore them to a girl's weekend! I tell all my friends about the Edit!</p>
                        <!-- Customer Name and Location -->
                        <p class="font-semibold text-gray-900">Tanya - Cork</p>
                    </div>
                    <!-- Testimonial Pagination Dots -->
                    <div class="flex justify-center space-x-2 mt-4">
                        <!-- Active Dot -->
                        <span class="w-3 h-3 bg-gray-900 rounded-full"></span>
                        <!-- Inactive Dots -->
                        <span class="w-3 h-3 bg-gray-300 rounded-full"></span>
                        <span class="w-3 h-3 bg-gray-300 rounded-full"></span>
                        <span class="w-3 h-3 bg-gray-300 rounded-full"></span>
                        <span class="w-3 h-3 bg-gray-300 rounded-full"></span>
                    </div>
                </div>
                
                <!-- Testimonial 3: London Customer -->
                <div class="text-center">
                    <div class="bg-gray">
                        <!-- Customer Quote -->
                        <p class="text-gray-600 italic mb-4">I bought a selection of sizes of matching sleepwear for my friend's hen party, and they were a perfect fit and so comfortable - we wore them all weekend! 5 stars!</p>
                        <!-- Customer Name and Location -->
                        <p class="font-semibold text-gray-900">Clara - London</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================================================
         MAIN FOOTER CONTENT
         ========================================================================== -->
    
    <!-- Main Footer Background (Brand Color) -->
    <div class="bg-[#E3E1DC] py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Four Column Responsive Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                
                <!-- ==================================================================
                     COLUMN 1: CONTACT INFORMATION
                     ================================================================== -->
                <div>
                    <!-- Section Heading -->
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 uppercase tracking-wider">Contact Us</h3>
                    <!-- Email Link -->
                    <a href="mailto:brantreeedit@outlook.com" class="text-gray-600 hover:text-gray-900 text-base inline-block mb-2">brantreeedit@outlook.com</a>
                </div>
                
                <!-- ==================================================================
                     COLUMN 2: PRODUCT CATEGORIES
                     ================================================================== -->
                <div>
                    <!-- Section Heading -->
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 uppercase tracking-wider">Shop The Edit</h3>
                    <!-- Navigation Links List -->
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 transition duration-150">Sleepwear</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 transition duration-150">Bags</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 transition duration-150">Jewellery</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 transition duration-150">Sunglasses</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 transition duration-150">Scarves</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 transition duration-150">Gifts</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900 transition duration-150">Owlet</a></li>
                    </ul>
                </div>
                
                <!-- ==================================================================
                     COLUMN 3: NEWSLETTER SIGNUP
                     ================================================================== -->
                <div>
                    <!-- Section Heading -->
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 uppercase tracking-wider">Join the party!</h3>
                    <!-- Value Proposition -->
                    <p class="text-gray-600 mb-4 text-center">Sign up to grab 10% off your next order. We believe you deserve a treat!</p>
                    
                    <!-- Email Signup Form -->
                    <form class="space-y-3">
                        <!-- Combined Input and Button Container -->
                        <div class="flex rounded-[20px] border border-gray-300 bg-white max-w-xs mx-auto">
                            <!-- Email Input Field -->
                            <input type="email" 
                                   placeholder="Enter your email" 
                                   class="flex-1 px-4 py-3 focus:outline-none text-gray-700 rounded-l-[20px] text-sm">
                            <!-- Submit Button -->
                            <button type="submit" 
                                    class="bg-gray-900 text-white py-3 px-6 hover:bg-gray-700 transition duration-200 font-semibold uppercase tracking-wider text-sm whitespace-nowrap rounded-r-[20px]">
                                Submit
                            </button>
                        </div>
                    </form>
                    
                    <!-- Legal Disclaimer -->
                    <p class="text-xs text-gray-500 mt-3 text-center">
                        By signing up, you agree to receive our latest offers and updates. See our 
                        <a href="#" class="underline hover:text-gray-900">privacy statement</a> and our 
                        <a href="#" class="underline hover:text-gray-900">terms & conditions</a>.
                    </p>
                </div>
                
                <!-- ==================================================================
                     COLUMN 4: HELP & SUPPORT LINKS
                     ================================================================== -->
                <div>
                    <!-- Section Heading -->
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 uppercase tracking-wider">Here to Help</h3>
                    <!-- Two Column Link Grid -->
                    <div class="grid grid-cols-2 gap-x-8 gap-y-2">
                        <a href="#" class="text-gray-600 hover:text-gray-900 transition duration-150 text-sm">Blog</a>
                        <a href="#" class="text-gray-600 hover:text-gray-900 transition duration-150 text-sm">Delivery</a>
                        <a href="#" class="text-gray-600 hover:text-gray-900 transition duration-150 text-sm">About</a>
                        <a href="#" class="text-gray-600 hover:text-gray-900 transition duration-150 text-sm">Returns</a>
                        <a href="#" class="text-gray-600 hover:text-gray-900 transition duration-150 text-sm">FAQs</a>
                        <a href="#" class="text-gray-600 hover:text-gray-900 transition duration-150 text-sm">Privacy Statement</a>
                        <a href="#" class="text-gray-600 hover:text-gray-900 transition duration-150 text-sm">Vouchers</a>
                        <a href="#" class="text-gray-600 hover:text-gray-900 transition duration-150 text-sm">Terms & Conditions</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================================================
         COPYRIGHT FOOTER
         ========================================================================== -->
    
    <!-- Copyright Section with Black Background -->
    <div class="bg-black py-6 w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Copyright and Attribution -->
            <div class="flex flex-col md:flex-row justify-between items-center">
                <!-- Copyright Text -->
                <div class="text-gray-400 text-sm mb-4 md:mb-0">
                    © 2023 Brantree Edit
                </div>
                <!-- Creator Attribution -->
                <div class="text-gray-500 text-sm">
                    Created by Akshay
                </div>
            </div>
        </div>
    </div>
</footer>