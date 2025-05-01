// Mobile Footer Functionality
(function($) {
    'use strict';
    
    // Function to check if device is mobile
    function isMobile() {
        return window.innerWidth <= 767;
    }
    
    // Function to set active menu item
    function setActiveMenuItem() {
        var currentPath = window.location.pathname;
        $('.mobile-footer-menu li a').each(function() {
            var linkPath = $(this).attr('href');
            if (currentPath.indexOf(linkPath) !== -1) {
                $(this).addClass('active');
                
                // Add ripple effect to active item
                if (!$(this).find('.ripple-effect').length) {
                    var ripple = $('<span class="ripple-effect"></span>');
                    $(this).append(ripple);
                    
                    setTimeout(function() {
                        ripple.addClass('animate');
                    }, 50);
                    
                    setTimeout(function() {
                        ripple.remove();
                    }, 700);
                }
            }
        });
    }
    
    // Add ripple effect to menu items
    function addRippleEffect() {
        $('.mobile-footer-menu li a').on('click', function(e) {
            var x = e.pageX - $(this).offset().left;
            var y = e.pageY - $(this).offset().top;
            
            var ripple = $('<span class="ripple-effect"></span>');
            ripple.css({
                top: y + 'px',
                left: x + 'px'
            });
            
            $(this).append(ripple);
            
            setTimeout(function() {
                ripple.remove();
            }, 700);
        });
    }
    
    // Add smooth page transitions
    function addSmoothTransitions() {
        $('.mobile-footer-menu li a').on('click', function(e) {
            // Don't apply to external links or anchors
            if (this.hostname === window.location.hostname && 
                this.pathname !== window.location.pathname && 
                !this.hash) {
                
                e.preventDefault();
                
                var href = this.href;
                
                // Add transition overlay
                var overlay = $('<div class="page-transition-overlay"></div>');
                $('body').append(overlay);
                
                setTimeout(function() {
                    overlay.addClass('active');
                }, 10);
                
                setTimeout(function() {
                    window.location.href = href;
                }, 300);
            }
        });
    }
    
    // Function to add "More" menu option
    function addMoreMenuOption() {
        // Always remove existing "More" menu to ensure we don't have duplicates
        $('.mobile-footer-menu li.more-menu').remove();
        
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
        $('#mobile-more-menu').off('click').on('click', function(e) {
            e.preventDefault();
            
            // Toggle active class
            $(this).toggleClass('active');
            
            // Toggle sidebar
            $('html').toggleClass('sidebar-left-opened');
            
            // Add ripple effect
            var x = e.pageX - $(this).offset().left;
            var y = e.pageY - $(this).offset().top;
            
            var ripple = $('<span class="ripple-effect"></span>');
            ripple.css({
                top: y + 'px',
                left: x + 'px'
            });
            
            $(this).append(ripple);
            
            setTimeout(function() {
                ripple.remove();
            }, 700);
        });
    }
    
    // Initialize mobile footer
    $(document).ready(function() {
        if (isMobile()) {
            $('body').addClass('has-mobile-footer');
            
            // Ensure the More menu is added
            setTimeout(function() {
                addMoreMenuOption();
                
                // Make sure the More menu is always visible
                if ($('.mobile-footer-menu li.more-menu').length === 0) {
                    addMoreMenuOption();
                }
            }, 100);
            
            setActiveMenuItem();
            addRippleEffect();
            addSmoothTransitions();
            
            // Add Material Design classes to mobile footer
            $('.mobile-footer').addClass('md-bottom-nav');
            $('.mobile-footer-menu li a').addClass('md-ripple');
            
            // Add a mutation observer to ensure the More menu is always present
            // This helps when the DOM changes after page load
            if (window.MutationObserver) {
                var observer = new MutationObserver(function(mutations) {
                    if ($('.mobile-footer-menu li.more-menu').length === 0) {
                        addMoreMenuOption();
                    }
                });
                
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            }
            
            // Add page transition styles
            if (!$('#page-transition-styles').length) {
                $('head').append(
                    '<style id="page-transition-styles">' +
                    '.page-transition-overlay {' +
                    '  position: fixed;' +
                    '  top: 0;' +
                    '  left: 0;' +
                    '  right: 0;' +
                    '  bottom: 0;' +
                    '  background-color: #fff;' +
                    '  z-index: 9999;' +
                    '  opacity: 0;' +
                    '  transition: opacity 0.3s ease;' +
                    '}' +
                    '.page-transition-overlay.active {' +
                    '  opacity: 1;' +
                    '}' +
                    'html.dark .page-transition-overlay {' +
                    '  background-color: #1c1b1f;' +
                    '}' +
                    '.ripple-effect {' +
                    '  position: absolute;' +
                    '  border-radius: 50%;' +
                    '  background-color: rgba(255, 255, 255, 0.3);' +
                    '  width: 100px;' +
                    '  height: 100px;' +
                    '  margin-top: -50px;' +
                    '  margin-left: -50px;' +
                    '  transform: scale(0);' +
                    '  opacity: 1;' +
                    '  animation: ripple 0.7s ease-out;' +
                    '}' +
                    '@keyframes ripple {' +
                    '  to {' +
                    '    transform: scale(2);' +
                    '    opacity: 0;' +
                    '  }' +
                    '}' +
                    '</style>'
                );
            }
        }
        
        // Handle window resize
        $(window).resize(function() {
            if (isMobile()) {
                $('body').addClass('has-mobile-footer');
                addMoreMenuOption();
                setActiveMenuItem();
            } else {
                $('body').removeClass('has-mobile-footer');
                // Remove "More" menu when not in mobile view
                $('.mobile-footer-menu li.more-menu').remove();
            }
        });
    });
    
})(jQuery);