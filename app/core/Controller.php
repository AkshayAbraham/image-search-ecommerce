<?php
/**
 * Abstract Controller Class
 * 
 * Base controller class that provides common functionality for all controllers
 * Handles view rendering, redirection, JSON responses, and flash messages
 * Implements the core MVC pattern for request handling and response generation
 * 
 * @package Core
 * @abstract
 * @author Akshay
 * @version 1.0
 */
abstract class Controller {
    
    /**
     * Render a view with data
     * 
     * Extracts data array into variables and includes the view file
     * Provides separation between business logic and presentation layer
     * 
     * @param string $view View file path relative to views directory (without extension)
     * @param array $data Associative array of data to pass to the view
     * @return void
     * @throws Exception If view file is not found
     */
    protected function view($view, $data = []) {
        // Extract data array to variables for view access
        extract($data);
        
        // Build full view file path
        $viewPath = BASE_PATH . "/views/{$view}.php";
        
        // Check if view exists and render, otherwise show error
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View not found: {$view}");
        }
    }

    /**
     * Redirect to specified URL
     * 
     * Sends HTTP redirect header and terminates script execution
     * Used for PRG (Post-Redirect-Get) pattern and navigation
     * 
     * @param string $url URL to redirect to (relative to base URL)
     * @return void
     * @codeCoverageIgnore
     */
    protected function redirect($url) {
        header("Location: /{$url}");
        exit();
    }

    /**
     * Send JSON response
     * 
     * Sets appropriate headers and outputs data as JSON
     * Useful for API endpoints and AJAX responses
     * 
     * @param mixed $data Data to be encoded as JSON
     * @return void
     */
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    /**
     * Set flash message for next request
     * 
     * Stores temporary message in session to display after redirect
     * Implements PRG (Post-Redirect-Get) pattern with user feedback
     * 
     * @param string $type Message type (e.g., 'success', 'error', 'warning')
     * @param string $message Message content to display
     * @return void
     */
    protected function setMessage($type, $message) {
        $_SESSION['flash_message'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /**
     * Get and clear flash message
     * 
     * Retrieves flash message from session and removes it to prevent re-display
     * Should be called in the view to display one-time messages
     * 
     * @return array|null Message array with 'type' and 'message' keys, or null if no message
     */
    protected function getMessage() {
        $message = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);
        return $message;
    }
}