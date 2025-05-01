// Mobile More Menu - Adds the "More" menu option to the mobile footer
(function($) {
    'use strict';
    
    // Function to check if device is mobile
    function isMobile() {
        return window.innerWidth <= 767;
    }
    
    // Function to add "More" menu option
    function addMoreMenuOption() {
        // Check if "More" menu already exists
        if ($('.mobile-footer-menu li.more-menu').length === 0) {
            // Add "More" menu item to mobile footer
            var moreMenuItem = $('<li class="more-menu">' +
                '<a href="#" id="mobile-more-menu">' +
                '<i class="fas fa-ellipsis-h"></i>' +
                '<span>More</span>' +
                '</a>' +
                '</li>');
            
            // Append to mobile footer menu
            $('.mobile-footer-menu').append(moreMenuItem);
            
            // Add click event to toggle sidebar
            $('#mobile-more-menu').on('click', function(e) {
                e.preventDefault();
                
                // Toggle sidebar
                $('html').toggleClass('sidebar-left-opened');
            });
        }
    }
    
    // Initialize on document ready
    $(document).ready(function() {
        if (isMobile()) {
            // Add the More menu
            addMoreMenuOption();
        }
        
        // Handle window resize
        $(window).resize(function() {
            if (isMobile()) {
                addMoreMenuOption();
            }
        });
    });
    
    // Also run when page is fully loaded
    $(window).on('load', function() {
        if (isMobile()) {
            addMoreMenuOption();
        }
    });
    
})(jQuery);