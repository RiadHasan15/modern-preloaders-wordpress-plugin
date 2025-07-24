/**
 * Modern Preloaders Admin Preview JavaScript
 *
 * @package ModernPreloaders
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // Handle preview item clicks
    $('.mpreloader-preview-item').on('click', function() {
        var $item = $(this);
        var loaderId = $item.data('loader');
        
        // Remove selected class from all items
        $('.mpreloader-preview-item').removeClass('selected');
        $('.mpreloader-preview-item').css({
            'border-color': '#ddd',
            'box-shadow': 'none'
        });
        
        // Add selected class to clicked item
        $item.addClass('selected');
        $item.css({
            'border-color': '#0073aa',
            'box-shadow': '0 0 10px rgba(0,115,170,0.3)'
        });
        
        // Update radio button
        $item.find('input[type="radio"]').prop('checked', true);
    });
    
    // Handle radio button changes
    $('input[name="mpreloader_selected"]').on('change', function() {
        var selectedValue = $(this).val();
        var $selectedItem = $('.mpreloader-preview-item[data-loader="' + selectedValue + '"]');
        
        // Remove selected styling from all items
        $('.mpreloader-preview-item').removeClass('selected');
        $('.mpreloader-preview-item').css({
            'border-color': '#ddd',
            'box-shadow': 'none'
        });
        
        // Add selected styling to the selected item
        $selectedItem.addClass('selected');
        $selectedItem.css({
            'border-color': '#0073aa',
            'box-shadow': '0 0 10px rgba(0,115,170,0.3)'
        });
    });
    
    // Initialize selected state on page load
    var $selectedRadio = $('input[name="mpreloader_selected"]:checked');
    if ($selectedRadio.length) {
        var selectedValue = $selectedRadio.val();
        var $selectedItem = $('.mpreloader-preview-item[data-loader="' + selectedValue + '"]');
        
        $selectedItem.addClass('selected');
        $selectedItem.css({
            'border-color': '#0073aa',
            'box-shadow': '0 0 10px rgba(0,115,170,0.3)'
        });
    }
    
    // Add hover effects
    $('.mpreloader-preview-item').hover(
        function() {
            if (!$(this).hasClass('selected')) {
                $(this).css({
                    'border-color': '#0073aa',
                    'transform': 'translateY(-2px)',
                    'box-shadow': '0 4px 12px rgba(0,0,0,0.1)'
                });
            }
        },
        function() {
            if (!$(this).hasClass('selected')) {
                $(this).css({
                    'border-color': '#ddd',
                    'transform': 'translateY(0)',
                    'box-shadow': 'none'
                });
            }
        }
    );
    
    // Smooth transitions
    $('.mpreloader-preview-item').css('transition', 'all 0.3s ease');
    
    // Form validation
    $('form').on('submit', function() {
        var selectedLoader = $('input[name="mpreloader_selected"]:checked').val();
        if (!selectedLoader) {
            alert('Please select a preloader before saving.');
            return false;
        }
        return true;
    });
    
    // Auto-save notification (optional enhancement)
    var timeoutId;
    $('input[name="mpreloader_enabled"], input[name="mpreloader_selected"]').on('change', function() {
        clearTimeout(timeoutId);
        
        // Show a subtle indication that settings have changed
        var $submitButton = $('input[type="submit"]');
        var originalText = $submitButton.val();
        
        $submitButton.val('Save Changes *').css('background-color', '#d63638');
        
        timeoutId = setTimeout(function() {
            $submitButton.val(originalText).css('background-color', '');
        }, 3000);
    });
});
