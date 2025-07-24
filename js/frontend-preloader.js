/**
 * Modern Preloaders Frontend JavaScript
 * Handles preloader overlay removal with multiple fallback mechanisms
 *
 * @package ModernPreloaders
 */

(function() {
    'use strict';
    
    var preloaderOverlay = document.getElementById('mpreloader-overlay');
    var isPreloaderHidden = false;
    
    // Exit if no preloader found
    if (!preloaderOverlay) {
        return;
    }
    
    // Function to hide preloader
    function hidePreloader() {
        if (isPreloaderHidden || !preloaderOverlay) {
            return;
        }
        
        isPreloaderHidden = true;
        
        // Add fade out transition
        preloaderOverlay.style.transition = 'opacity 0.5s ease-out';
        preloaderOverlay.style.opacity = '0';
        
        // Remove element after fade
        setTimeout(function() {
            if (preloaderOverlay && preloaderOverlay.parentNode) {
                preloaderOverlay.parentNode.removeChild(preloaderOverlay);
            }
        }, 500);
    }
    
    // Method 1: Wait for window load event
    if (window.addEventListener) {
        window.addEventListener('load', hidePreloader, false);
    } else if (window.attachEvent) {
        window.attachEvent('onload', hidePreloader);
    }
    
    // Method 2: Check if document is already loaded
    if (document.readyState === 'complete') {
        setTimeout(hidePreloader, 100);
    } else {
        // Method 3: Listen for readystate changes
        document.addEventListener('readystatechange', function() {
            if (document.readyState === 'complete') {
                setTimeout(hidePreloader, 100);
            }
        });
    }
    
    // Method 4: jQuery fallback (if available)
    if (typeof jQuery !== 'undefined') {
        jQuery(document).ready(function($) {
            $(window).on('load', hidePreloader);
            
            // Additional check after DOM ready
            if (document.readyState === 'complete') {
                setTimeout(hidePreloader, 50);
            }
        });
    }
    
    // Method 5: Absolute failsafe - hide after maximum time
    setTimeout(function() {
        if (!isPreloaderHidden) {
            console.log('Modern Preloaders: Using failsafe timeout to hide preloader');
            hidePreloader();
        }
    }, 8000);
    
    // Method 6: Hide on any user interaction (click, scroll, key press)
    var userInteractionHandler = function() {
        if (!isPreloaderHidden) {
            setTimeout(hidePreloader, 100);
        }
    };
    
    // Add interaction listeners
    if (document.addEventListener) {
        document.addEventListener('click', userInteractionHandler, { once: true });
        document.addEventListener('scroll', userInteractionHandler, { once: true });
        document.addEventListener('keydown', userInteractionHandler, { once: true });
        document.addEventListener('touchstart', userInteractionHandler, { once: true });
    }
    
    // Method 7: Intersection Observer fallback
    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function(entries) {
            if (entries.length > 0 && !isPreloaderHidden) {
                setTimeout(hidePreloader, 200);
                observer.disconnect();
            }
        });
        
        // Observe the first element in body
        if (document.body && document.body.children.length > 1) {
            observer.observe(document.body.children[1]); // Skip the preloader overlay
        }
    }
    
})();