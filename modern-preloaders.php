<?php
/**
 * Plugin Name: Modern Preloaders - 25 CSS Loaders
 * Plugin URI: https://github.com/RiadHasan15/modern-preloaders-wordpress-plugin.git
 * Description: A collection of 25 modern, visually attractive CSS preloaders with easy admin controls and site-wide implementation.
 * Version: 1.0.0
 * Author: Riad Hasan
 * Author URI: https://riadhasan.info/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: modern-preloaders
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * Network: false
 *
 * @package ModernPreloaders
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('MPRELOADER_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MPRELOADER_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('MPRELOADER_VERSION', '1.0.0');

/**
 * Main Modern Preloaders Class
 */
class ModernPreloaders {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('wp_footer', array($this, 'output_preloader'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        load_plugin_textdomain('modern-preloaders', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Set default options
        add_option('mpreloader_enabled', '0');
        add_option('mpreloader_selected', '1');
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Clean up if needed
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_options_page(
            __('Preloader Settings', 'modern-preloaders'),
            __('Preloader Settings', 'modern-preloaders'),
            'manage_options',
            'modern-preloaders',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Admin page callback
     */
    public function admin_page() {
        require_once MPRELOADER_PLUGIN_PATH . 'admin-settings.php';
    }
    
    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_frontend_scripts() {
        if (get_option('mpreloader_enabled', '0') === '1') {
            wp_enqueue_style(
                'mpreloader-styles',
                MPRELOADER_PLUGIN_URL . 'css/preloaders.css',
                array(),
                MPRELOADER_VERSION
            );
            
            // Create separate JavaScript file for better control
            wp_enqueue_script(
                'mpreloader-frontend-js',
                MPRELOADER_PLUGIN_URL . 'js/frontend-preloader.js',
                array('jquery'),
                MPRELOADER_VERSION,
                true
            );
        }
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'settings_page_modern-preloaders') {
            return;
        }
        
        wp_enqueue_style(
            'mpreloader-admin-styles',
            MPRELOADER_PLUGIN_URL . 'css/preloaders.css',
            array(),
            MPRELOADER_VERSION
        );
        
        wp_enqueue_script(
            'mpreloader-admin-js',
            MPRELOADER_PLUGIN_URL . 'js/admin-preview.js',
            array('jquery'),
            MPRELOADER_VERSION,
            true
        );
    }
    
    /**
     * Output preloader on frontend
     */
    public function output_preloader() {
        if (get_option('mpreloader_enabled', '0') !== '1') {
            return;
        }
        
        $selected_loader = get_option('mpreloader_selected', '1');
        $loader_file = MPRELOADER_PLUGIN_PATH . 'preloaders/loader' . intval($selected_loader) . '.html';
        
        if (file_exists($loader_file)) {
            $overlay_styles = array(
                'position: fixed',
                'top: 0',
                'left: 0', 
                'width: 100%',
                'height: 100%',
                'background: #ffffff',
                'z-index: 999999',
                'display: flex',
                'align-items: center',
                'justify-content: center',
                'opacity: 1',
                'transition: none'
            );
            
            echo '<div id="mpreloader-overlay" data-preloader="' . esc_attr($selected_loader) . '" style="' . esc_attr(implode('; ', $overlay_styles)) . ';">';
            echo wp_kses_post(file_get_contents($loader_file));
            echo '</div>';
            
            // Add inline script for immediate execution
            echo '<script type="text/javascript">';
            echo 'document.getElementById("mpreloader-overlay").setAttribute("data-timestamp", Date.now());';
            echo '</script>';
        }
    }
    
    /**
     * Get loader HTML content
     */
    public function get_loader_html($loader_id) {
        $loader_file = MPRELOADER_PLUGIN_PATH . 'preloaders/loader' . intval($loader_id) . '.html';
        
        if (file_exists($loader_file)) {
            return file_get_contents($loader_file);
        }
        
        return '';
    }
}

// Initialize the plugin
new ModernPreloaders();
