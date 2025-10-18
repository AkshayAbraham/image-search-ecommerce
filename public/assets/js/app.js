/**
 * E-commerce Application JavaScript Module
 * 
 * Handles interactive features for BRANTREE EDIT fashion platform
 * Implements search functionality, modal management, and image processing
 * Provides smooth user interactions with accessibility considerations
 * 
 * @package Scripts
 * @author Akshay
 * @version 1.0
 * 
 * @architecture Module pattern with event-driven design
 * @accessibility Keyboard navigation and screen reader support
 * @performance Optimized event handlers and DOM operations
 */

/* ==========================================================================
   SEARCH INTERFACE MANAGEMENT
   ========================================================================== */

/**
 * Search State Management
 * 
 * Tracks the open/closed state of the expandable search interface
 * Ensures consistent UI behavior across user interactions
 * 
 * @type {boolean} Global state tracking for search component
 */
let isSearchOpen = false;

/**
 * Toggle Search Interface Visibility
 * 
 * Controls the expandable search bar with smooth animations
 * Manages focus for accessibility and keyboard navigation
 * Implements proper timing for CSS transition synchronization
 * 
 * @ui_component Expandable search input with camera icon
 * @animation 500ms ease-in-out transition with proper timing
 * @accessibility Manages focus for screen reader users
 */
function toggleSearch() {
    const searchContainer = document.getElementById('searchContainer');
    const searchToggle = document.getElementById('searchToggle');
    
    if (!isSearchOpen) {
        // Open search with smooth animation sequence
        searchContainer.classList.remove('hidden');
        // Small delay ensures CSS transition triggers properly
        setTimeout(() => {
            searchContainer.classList.add('flex');
            document.getElementById('searchInput').focus(); // Accessibility focus
        }, 50);
        isSearchOpen = true;
    } else {
        // Close search with completion-aware animation
        searchContainer.classList.remove('flex');
        // Wait for CSS transition to complete before hiding
        setTimeout(() => {
            searchContainer.classList.add('hidden');
        }, 400); // Matches CSS transition duration
        isSearchOpen = false;
    }
}

/**
 * Close Search on External Click
 * 
 * Implements click-outside detection for better UX
 * Prevents search interface from staying open unintentionally
 * 
 * @ux_pattern Click-outside to close interactive components
 * @event_handling Efficient single event listener for entire document
 */
document.addEventListener('click', function(event) {
    const searchContainer = document.getElementById('searchContainer');
    const searchToggle = document.getElementById('searchToggle');
    
    // Close search if click is outside search components
    if (isSearchOpen && 
        !searchContainer.contains(event.target) && 
        !searchToggle.contains(event.target)) {
        searchContainer.classList.remove('flex');
        setTimeout(() => {
            searchContainer.classList.add('hidden');
        }, 400);
        isSearchOpen = false;
    }
});

/* ==========================================================================
   TEXT SEARCH FUNCTIONALITY
   ========================================================================== */

/**
 * Initialize Text Search Event Listeners
 * 
 * Sets up keyboard interaction for search input field
 * Enables Enter key submission for accessibility
 * 
 * @dom_ready Ensures elements exist before attaching listeners
 * @keyboard_support Enter key submission for power users
 */
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performTextSearch(this.value);
            }
        });
    }
});

/**
 * Execute Text-Based Product Search
 * 
 * Handles text search queries with input validation
 * Provides user feedback and manages UI state
 * 
 * @feature Text-based product search (placeholder implementation)
 * @validation Prevents empty search queries
 * @ux_flow Clears input and closes search after execution
 * 
 * @param {string} query - User search query from input field
 * 
 * @todo Integrate with backend search API
 * @todo Implement product name and description matching
 */
function performTextSearch(query) {
    if (query.trim() !== '') {
        // Placeholder implementation - can be extended with actual search
        alert('Text search for: ' + query + '\n\nThis feature can be implemented to search products by name or description.');
        
        // Clear input for next search
        document.getElementById('searchInput').value = '';
        
        // Close search interface after execution
        toggleSearch();
    }
}

/* ==========================================================================
   IMAGE SEARCH MODAL MANAGEMENT
   ========================================================================== */

/**
 * Open Image Search Modal
 * 
 * Displays modal for visual product search functionality
 * Manages competing UI states (closes text search if open)
 * 
 * @feature AI-powered visual product search interface
 * @state_management Handles conflicting UI component states
 */
function openImageSearch() {
    document.getElementById('imageSearchModal').classList.remove('hidden');
    // Ensure clean state by closing text search if open
    if (isSearchOpen) {
        toggleSearch();
    }
}

/**
 * Close Image Search Modal
 * 
 * Hides the image search modal interface
 * Maintains modal pattern consistency
 */
function closeImageSearch() {
    document.getElementById('imageSearchModal').classList.add('hidden');
}

/**
 * Close Modal on Backdrop Click
 * 
 * Implements standard modal behavior - close when clicking outside content
 * Enhances UX by providing intuitive dismissal method
 * 
 * @ux_pattern Backdrop click to close modal
 * @event_delegation Efficient single listener on modal container
 */
document.getElementById('imageSearchModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageSearch();
    }
});

/* ==========================================================================
   IMAGE PREVIEW AND VALIDATION
   ========================================================================== */

/**
 * Preview Selected Image with FileReader API
 * 
 * Handles image file selection and client-side preview generation
 * Updates UI state based on file selection validity
 * Provides immediate visual feedback for user actions
 * 
 * @technology FileReader API for client-side image processing
 * @ux_feedback Immediate visual confirmation of file selection
 * @validation Enables/disables search based on file presence
 * 
 * @param {Event} event - File input change event
 */
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('preview');
    const uploadIcon = document.getElementById('uploadIcon');
    const imagePreview = document.getElementById('imagePreview');
    const fileName = document.getElementById('fileName');
    const searchButton = document.getElementById('searchButton');
    const clearButton = document.getElementById('clearButton');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        /**
         * FileReader Load Event Handler
         * 
         * Processes selected image file and updates UI components
         * Manages visual state transitions and button states
         */
        reader.onload = function(e) {
            // Display image preview
            preview.src = e.target.result;
            uploadIcon.classList.add('hidden');
            imagePreview.classList.remove('hidden');
            
            // Show selected file name
            fileName.textContent = "Selected: " + input.files[0].name;
            fileName.classList.remove('hidden');
            
            // Enable search functionality
            searchButton.disabled = false;
            searchButton.classList.remove('opacity-50', 'cursor-not-allowed');
            searchButton.classList.add('cursor-pointer');
            
            // Show clear option
            clearButton.classList.remove('hidden');
        }
        
        // Read image file as Data URL for preview
        reader.readAsDataURL(input.files[0]);
    }
}

/**
 * Clear Selected Image and Reset UI
 * 
 * Resets image search interface to initial state
 * Provides escape hatch for user file selection changes
 * 
 * @ux_pattern Clear action for file inputs
 * @state_management Resets multiple UI components
 */
function clearImage() {
    // Reset file input element
    document.getElementById('modal_product_image').value = '';
    
    // Hide preview elements
    document.getElementById('uploadIcon').classList.remove('hidden');
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('fileName').classList.add('hidden');
    document.getElementById('clearButton').classList.add('hidden');
    
    // Disable search until new image is selected
    document.getElementById('searchButton').disabled = true;
    document.getElementById('searchButton').classList.add('opacity-50', 'cursor-not-allowed');
    document.getElementById('searchButton').classList.remove('cursor-pointer');
}

/* ==========================================================================
   FORM VALIDATION AND SUBMISSION HANDLING
   ========================================================================== */

/**
 * Prevent Empty Image Form Submission
 * 
 * Client-side validation for image search form
 * Provides immediate user feedback for required fields
 * 
 * @validation Client-side form validation
 * @ux_feedback Prevents silent form submission failures
 * @accessibility Alert provides clear error message
 */
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const fileInput = document.getElementById('modal_product_image');
            if (fileInput && (!fileInput.files || !fileInput.files[0])) {
                e.preventDefault();
                alert('Please select an image first.');
            }
        });
    }
});

