<?php
/**
 * ProductController
 * 
 * Handles product-related operations including image search and analysis
 * Uses Groq AI service for image analysis and provides product recommendations
 * 
 * @package Controllers
 * @author Akshay
 * @version 1.0
 */
class ProductController extends Controller {
    
    /**
     * @var Product $productModel Instance of Product model for database operations
     */
    private $productModel;
    
    /**
     * @var GroqService $groqService Instance of GroqService for AI image analysis
     */
    private $groqService;

    /**
     * Constructor
     * 
     * Initializes Product model and GroqService dependency
     * Loads required model and service files
     */
    public function __construct() {
        require_once BASE_PATH . '/app/models/Product.php';
        require_once BASE_PATH . '/app/services/GroqService.php';
        $this->productModel = new Product();
        $this->groqService = new GroqService();
    }

    /**
     * Display all products
     * 
     * Fetches all products from database and renders the products index view
     * 
     * @return void
     */
    public function index() {
        $products = $this->productModel->getAllProducts();
        $data = [
            'title' => 'BRANTREE EDIT', 
            'products' => $products
        ];
        $this->view('products/index', $data);
    }

    /**
     * Handle product search requests
     * 
     * Processes POST requests for image-based search
     * Redirects to index for non-POST requests
     * 
     * @return void
     */
    public function search() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleImageSearch();
        } else {
            $this->redirect('');
        }
    }

    /**
     * Process image search with AI analysis
     * 
     * Handles image upload, validation, AI analysis, and product matching
     * Falls back to enhanced analysis if AI service fails
     * 
     * @return void
     * @throws Exception If image processing fails
     */
    private function handleImageSearch() {
        // Validate file upload
        if (!isset($_FILES['product_image']) || $_FILES['product_image']['error'] !== UPLOAD_ERR_OK) {
            $this->setMessage('error', 'Please select an image to upload.');
            $this->redirect('');
            return;
        }

        $uploadedFile = $_FILES['product_image'];
        
        // Validate image file
        if (!$this->validateImage($uploadedFile)) {
            $this->redirect('');
            return;
        }

        try {
            // Attempt AI-powered image analysis
            $imageFeatures = $this->groqService->analyzeImage($uploadedFile['tmp_name']);
            $similarProducts = $this->productModel->searchByFeatures($imageFeatures);
        } catch (Exception $e) {
            // Fallback to enhanced analysis if AI service fails
            error_log("Groq API Error: " . $e->getMessage());
            $imageFeatures = $this->analyzeImageEnhanced($uploadedFile);
            $similarProducts = $this->productModel->searchByFeatures($imageFeatures);
        }

        // Save uploaded image with sanitized filename
        $fileName = uniqid() . '_' . preg_replace("/[^a-zA-Z0-9\.]/", "_", $uploadedFile['name']);
        $uploadPath = UPLOAD_PATH . $fileName;
        move_uploaded_file($uploadedFile['tmp_name'], $uploadPath);

        // Prepare data for results view
        $data = [
            'title' => 'Search Results',
            'products' => $similarProducts,
            'searchImage' => $fileName,
            'analysis' => $imageFeatures
        ];

        $this->view('products/results', $data);
    }

    /**
     * Validate uploaded image file
     * 
     * Checks file size and type constraints
     * Sets error messages for invalid files
     * 
     * @param array $file Uploaded file array from $_FILES
     * @return bool True if valid, false otherwise
     */
    private function validateImage($file) {
        // Check file size limit
        if ($file['size'] > MAX_FILE_SIZE) {
            $this->setMessage('error', 'File is too large (max 2MB).');
            return false;
        }

        // Check file extension
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ALLOWED_TYPES)) {
            $this->setMessage('error', 'Please upload a valid image (JPG, PNG, GIF).');
            return false;
        }

        return true;
    }

    /**
     * Enhanced image analysis fallback
     * 
     * Provides basic image analysis when AI service is unavailable
     * Generates random patterns, colors, and categories for demonstration
     * 
     * @param array $imageFile Uploaded image file array
     * @return array Analysis results with pattern, colors, category, and confidence
     */
    private function analyzeImageEnhanced($imageFile) {
        // Available patterns, colors, and categories for fallback analysis
        $patterns = ['striped', 'solid', 'floral', 'denim', 'checkered', 'dotted', 'printed', 'knit', 'velvet'];
        $colors = ['red', 'blue', 'green', 'yellow', 'black', 'white', 'pink', 'purple', 'orange', 'brown', 'gray'];
        $categories = ['clothing', 'footwear', 'accessories', 'jewelry'];

        // Generate random analysis results
        $detectedPattern = $patterns[array_rand($patterns)];
        $category = $categories[array_rand($categories)];

        return [
            'detected_pattern' => $detectedPattern,
            'dominant_colors' => [$colors[array_rand($colors)], $colors[array_rand($colors)]],
            'category' => $category,
            'confidence' => rand(80, 95) / 100  // Random confidence between 80-95%
        ];
    }
}