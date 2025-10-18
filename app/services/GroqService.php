<?php
/**
 * Groq AI Service Class
 * 
 * Integrates with Groq Cloud API for AI-powered image analysis
 * Specializes in fashion product recognition and feature extraction
 * Provides computer vision capabilities for visual search functionality
 * Implements robust error handling and response validation
 * 
 * @package Services
 * @author Akshay
 * @version 1.0
 */
class GroqService {
    
    /**
     * @var string $apiKey Groq Cloud API key for authentication
     */
    private $apiKey;
    
    /**
     * @var string $apiUrl Groq API endpoint URL
     */
    private $apiUrl;
    
    /**
     * @var string $model Groq AI model identifier (e.g., 'llama-3.2-11b-vision-preview')
     */
    private $model;
    
    /**
     * Constructor
     * 
     * Initializes Groq API configuration from environment constants
     * Validates API key presence and proper configuration
     * Ensures service is properly configured before use
     * 
     * @throws Exception If API key is not configured or invalid
     */
    public function __construct() {
        // Load configuration from environment constants (defined in config.php)
        $this->apiKey = GROQ_API_KEY;
        $this->apiUrl = GROQ_API_URL;
        $this->model = GROQ_MODEL;
        
        // Validate critical configuration parameters
        if (empty($this->apiKey) || $this->apiKey === 'your_actual_groq_api_key_here') {
            throw new Exception("Groq API key not configured. Please check your .env file.");
        }
    }

    /**
     * Analyze image using Groq Vision model
     * 
     * Processes product images through AI to extract fashion-related features
     * Implements comprehensive file validation and preprocessing
     * Uses specialized prompt engineering for fashion classification
     * 
     * @param string $imagePath Path to the image file for analysis
     * @return array Structured analysis containing:
     *               - detected_pattern: Primary pattern identified
     *               - dominant_colors: Array of two dominant colors
     *               - category: Product category classification
     *               - confidence: AI confidence score (0.0-1.0)
     *               - raw_analysis: Complete raw AI response
     * @throws Exception If image file is invalid, API fails, or response is malformed
     * 
     * @process
     * 1. File Validation → 2. Image Encoding → 3. API Request → 4. Response Parsing
     */
    public function analyzeImage($imagePath) {
        // Validate image file existence and accessibility
        if (!file_exists($imagePath) || !is_readable($imagePath)) {
            throw new Exception("Image file not found or not readable: {$imagePath}");
        }

        // Enforce file size limits (Groq API constraints)
        $fileSize = filesize($imagePath);
        if ($fileSize > 5 * 1024 * 1024) { // 5MB limit
            throw new Exception("Image too large for Groq API: {$fileSize} bytes");
        }

        // Encode image to base64 for API transmission
        $imageData = base64_encode(file_get_contents($imagePath));
        $mimeType = mime_content_type($imagePath);

        // Validate supported image formats
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($mimeType, $allowedMimeTypes)) {
            throw new Exception("Unsupported image type: {$mimeType}");
        }

        /**
         * Advanced Prompt Engineering for Fashion Classification
         * 
         * Key Strategies:
         * - Domain-specific context forcing (fashion-only)
         * - Strict JSON output formatting requirement
         * - Controlled vocabulary for consistent responses
         * - Clear classification guidelines and fallbacks
         */
        $prompt = <<<PROMPT
You are an expert FASHION PRODUCT classifier. 
Your task is to analyze an uploaded product image and describe what kind of fashion product it shows.

Always assume the image is from an online fashion or clothing store (e.g. shirts, dresses, shoes, bags, jewelry).
Never return random objects outside fashion context.

Return ONLY a JSON object in this exact format:
{
  "patterns": ["choose_one_pattern"],
  "colors": ["color1", "color2"],
  "category": "exact_category",
  "confidence": 0.85
}

PATTERNS - Choose ONE ONLY from: striped, solid, floral, denim, checkered, dotted, printed, graphic, plain, knit, velvet, pleated, leather
COLORS - Choose up to 2 from: red, blue, green, yellow, black, white, pink, purple, orange, brown, gray, navy, beige, cream, burgundy, khaki
CATEGORY - Choose ONE ONLY from: clothing, footwear, accessories, jewelry

Guidelines:
- If the image shows wearable items (shirt, dress, pants, jacket, skirt, coat, sweater, top, t-shirt, hoodie, etc.), ALWAYS choose "clothing".
- If you are unsure, prefer "clothing" over other categories.
- Return ONLY the JSON object, with no extra explanations.
PROMPT;

        // Construct API payload with multimodal content (text + image)
        $data = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $prompt
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => "data:{$mimeType};base64,{$imageData}"
                            ]
                        ]
                    ]
                ]
            ],
            'max_tokens' => 500,
            'temperature' => 0.1, // Low temperature for consistent, deterministic output
        ];

        try {
            $response = $this->makeApiRequest($data);
            return $this->parseResponse($response);
        } catch (Exception $e) {
            // Log detailed error for debugging while providing user-friendly exception
            error_log("Groq API Error: " . $e->getMessage());
            throw $e; // Re-throw to be handled by controller
        }
    }

    /**
     * Execute Groq API HTTP request
     * 
     * Handles low-level HTTP communication with Groq Cloud API
     * Manages authentication, headers, and error responses
     * Implements proper connection timeout and SSL verification
     * 
     * @param array $data Request payload for Groq API
     * @return string Raw API response JSON
     * @throws Exception For HTTP errors, timeouts, or connection failures
     */
    private function makeApiRequest($data) {
        $ch = curl_init();
        
        // Configure cURL options for secure API communication
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->apiKey
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30, // 30-second timeout for AI processing
            CURLOPT_SSL_VERIFYPEER => true, // Enable SSL verification for security
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        // Handle HTTP status codes with descriptive error messages
        if ($httpCode !== 200) {
            $errorMessage = "Groq API Error: HTTP {$httpCode}";
            if ($httpCode === 400) $errorMessage .= " - Bad Request";
            elseif ($httpCode === 401) $errorMessage .= " - Unauthorized (check API key)";
            elseif ($httpCode === 429) $errorMessage .= " - Rate Limit Exceeded";
            elseif ($httpCode === 404) $errorMessage .= " - Model Not Found";
            
            $errorMessage .= " - {$error}";
            throw new Exception($errorMessage);
        }

        if (!$response) {
            throw new Exception("Empty response from Groq API");
        }

        return $response;
    }

    /**
     * Parse and validate Groq API response
     * 
     * Extracts structured data from AI response with robust error handling
     * Implements JSON validation and data normalization
     * Ensures consistent output format regardless of AI variations
     * 
     * @param string $response Raw JSON response from Groq API
     * @return array Normalized analysis data with validated structure
     * @throws Exception For malformed JSON, missing data, or validation failures
     */
    private function parseResponse($response) {
        $result = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON response from Groq API");
        }

        $analysisText = $result['choices'][0]['message']['content'] ?? '';

        if (empty($analysisText)) {
            throw new Exception("Empty content in Groq response");
        }

        // Extract JSON from potential text wrapping using regex
        preg_match('/\{[^}]*\}/s', $analysisText, $matches);

        if (empty($matches)) {
            throw new Exception("No valid JSON found in Groq response: " . substr($analysisText, 0, 200));
        }

        $analysis = json_decode($matches[0], true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Malformed JSON in Groq output: " . json_last_error_msg());
        }

        // Extract and validate key features with fallback values
        $pattern = $analysis['patterns'][0] ?? 'solid';
        $colors = $analysis['colors'] ?? ['blue', 'white'];
        $category = $analysis['category'] ?? 'clothing';
        $confidence = $analysis['confidence'] ?? 0.85;

        // Ensure colors array has exactly 2 elements for consistent processing
        if (count($colors) < 2) {
            $colors[] = 'white'; // Default fallback color
        }
        $colors = array_slice($colors, 0, 2);

        return [
            'detected_pattern' => $pattern,
            'dominant_colors' => $colors,
            'category' => $category,
            'confidence' => $confidence,
            'raw_analysis' => $analysis // Include raw data for debugging and extensibility
        ];
    }
}