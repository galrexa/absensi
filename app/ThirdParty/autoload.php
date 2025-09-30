<?php

/**
 * Manual Autoloader for PDF Libraries in CodeIgniter 4
 * 
 * Libraries supported:
 * - mPDF
 * - PSR-Log
 * - FPDI
 * - FPDF
 * - SetaPDF (optional)
 * 
 * Usage: require_once APPPATH . 'ThirdParty/autoload.php';
 */

// Prevent direct access
if (!defined('BASEPATH') && !defined('APPPATH')) {
    exit('No direct script access allowed');
}

// Define base paths for libraries
define('THIRD_PARTY_PATH', APPPATH . 'ThirdParty' . DIRECTORY_SEPARATOR);
define('MPDF_PATH', THIRD_PARTY_PATH . 'mpdf' . DIRECTORY_SEPARATOR);
define('FPDI_PATH', THIRD_PARTY_PATH . 'fpdi' . DIRECTORY_SEPARATOR);
define('FPDF_PATH', THIRD_PARTY_PATH . 'fpdf' . DIRECTORY_SEPARATOR);
define('PSR_LOG_PATH', THIRD_PARTY_PATH . 'psr-log' . DIRECTORY_SEPARATOR);

/**
 * Custom autoloader function
 */
spl_autoload_register(function ($className) {
    
    // Convert namespace separators to directory separators
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    
    // mPDF Library
    if (strpos($className, 'Mpdf\\') === 0) {
        $file = MPDF_PATH . 'src' . DIRECTORY_SEPARATOR . substr($classPath, 5) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    
    // PSR-3 Logger Interface
    if (strpos($className, 'Psr\\Log\\') === 0) {
        $file = PSR_LOG_PATH . 'src' . DIRECTORY_SEPARATOR . substr($classPath, 8) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
        // Alternative path structure
        $file = PSR_LOG_PATH . substr($classPath, 8) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    
    // FPDI Library
    if (strpos($className, 'setasign\\Fpdi\\') === 0) {
        $file = FPDI_PATH . 'src' . DIRECTORY_SEPARATOR . substr($classPath, 14) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    
    // Alternative FPDI namespace
    if (strpos($className, 'Fpdi\\') === 0) {
        $file = FPDI_PATH . 'src' . DIRECTORY_SEPARATOR . substr($classPath, 5) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    
    // SetaPDF Core (if using FPDI with SetaPDF)
    if (strpos($className, 'setasign\\SetaPdf\\') === 0) {
        $file = THIRD_PARTY_PATH . 'setapdf-core' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . substr($classPath, 17) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    
    // Generic PSR-4 autoloading for other libraries
    $prefixes = [
        'DeepCopy\\' => THIRD_PARTY_PATH . 'deepcopy' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR,
        'Symfony\\' => THIRD_PARTY_PATH . 'symfony' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR,
    ];
    
    foreach ($prefixes as $prefix => $baseDir) {
        if (strpos($className, $prefix) === 0) {
            $relativeClass = substr($className, strlen($prefix));
            $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

// Load FPDF manually (not namespaced)
if (!class_exists('FPDF')) {
    $fpdfFile = FPDF_PATH . 'fpdf.php';
    if (file_exists($fpdfFile)) {
        require_once $fpdfFile;
    }
}

// Load additional required files for mPDF
if (defined('MPDF_PATH')) {
    // mPDF configuration
    $mpdfConfigFile = MPDF_PATH . 'src' . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'ConfigVariables.php';
    if (file_exists($mpdfConfigFile)) {
        require_once $mpdfConfigFile;
    }
    
    $mpdfFontConfigFile = MPDF_PATH . 'src' . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'FontVariables.php';
    if (file_exists($mpdfFontConfigFile)) {
        require_once $mpdfFontConfigFile;
    }
}

/**
 * Helper function to check if all required libraries are loaded
 */
function checkPdfLibraries() {
    $status = [
        'FPDF' => class_exists('FPDF'),
        'mPDF' => class_exists('Mpdf\\Mpdf'),
        'FPDI' => class_exists('setasign\\Fpdi\\Fpdi') || class_exists('Fpdi\\Fpdi'),
        'PSR-Log' => interface_exists('Psr\\Log\\LoggerInterface'),
    ];
    
    return $status;
}

/**
 * Initialize mPDF with CodeIgniter 4 compatible settings
 */
function initMpdf($config = []) {
    if (!class_exists('Mpdf\\Mpdf')) {
        throw new Exception('mPDF library not found. Please check your installation.');
    }
    
    $defaultConfig = [
        'tempDir' => WRITEPATH . 'cache',
        'fontDir' => [
            MPDF_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'mpdf' . DIRECTORY_SEPARATOR . 'mpdf' . DIRECTORY_SEPARATOR . 'ttfonts',
        ],
        'fontdata' => [],
        'default_font_size' => 12,
        'default_font' => 'helvetica',
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 16,
        'margin_bottom' => 16,
        'margin_header' => 9,
        'margin_footer' => 9,
        'orientation' => 'P',
        'format' => 'A4'
    ];
    
    $config = array_merge($defaultConfig, $config);
    
    try {
        $mpdf = new \Mpdf\Mpdf($config);
        return $mpdf;
    } catch (Exception $e) {
        throw new Exception('Failed to initialize mPDF: ' . $e->getMessage());
    }
}

/**
 * Initialize FPDI
 */
function initFpdi() {
    if (class_exists('setasign\\Fpdi\\Fpdi')) {
        return new \setasign\Fpdi\Fpdi();
    } elseif (class_exists('Fpdi\\Fpdi')) {
        return new \Fpdi\Fpdi();
    } elseif (class_exists('FPDI')) {
        return new FPDI();
    } else {
        throw new Exception('FPDI library not found.');
    }
}

// Auto-detect and load common configurations
if (function_exists('log_message')) {
    log_message('info', 'PDF Libraries autoloader initialized');
}

// Set memory limit for PDF processing
if (function_exists('ini_set')) {
    ini_set('memory_limit', '256M');
    ini_set('max_execution_time', 300);
}