// Mobile Footer Refresh Script
(function($) {
    'use strict';
    
    // Function to reload the mobile footer
    function reloadMobileFooter() {
        // Get the current mobile footer
        var $mobileFooter = $('.mobile-footer');
        
        if ($mobileFooter.length) {
            // Get user role ID
            var roleId = 0;
            
            // First try to get role ID from the data attribute if available
            if (typeof loggedin_role_id !== 'undefined') {
                roleId = loggedin_role_id;
                console.log('Using role ID from global variable:', roleId);
            } 
            // Fallback to body classes
            else if ($('body').hasClass('student-logged-in')) {
                roleId = 7; // Student role ID
            } else if ($('body').hasClass('parent-logged-in')) {
                roleId = 6; // Parent role ID
            } else if ($('body').hasClass('teacher-logged-in')) {
                roleId = 3; // Teacher role ID
            } else if ($('body').hasClass('admin-logged-in')) {
                roleId = 2; // Admin role ID
            } else if ($('body').hasClass('superadmin-logged-in')) {
                roleId = 1; // Superadmin role ID
            }
            
            console.log('Detected role ID:', roleId);
            
            // Only proceed if we have a valid role ID
            if (roleId > 0) {
                // Make AJAX request to get updated menu items
                $.ajax({
                    url: base_url + 'settings_footer/get_menu_items',
                    type: 'POST',
                    data: {
                        role_id: roleId
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Clear existing menu
                        $('.mobile-footer-menu').empty();
                        
                        // Build new menu based on selected items
                        var menuItems = response.selected_items;
                        
                        // If no items selected, use default items
                        if (menuItems.length === 0) {
                            menuItems = ['dashboard'];
                            
                            if (roleId === 7 || roleId === 6) {
                                menuItems.push('homework');
                                menuItems.push('attendance');
                                menuItems.push('fees');
                            } else {
                                menuItems.push('students');
                                menuItems.push('payments');
                                menuItems.push('attendance');
                                menuItems.push('homework');
                                menuItems.push('fees');
                            }
                            
                            menuItems.push('message');
                        }
                        
                        // Build menu HTML
                        var menuHtml = '';
                        
                        // Loop through menu items
                        $.each(menuItems, function(index, item) {
                            switch (item) {
                                case 'dashboard':
                                    menuHtml += '<li><a href="' + base_url + 'dashboard"><i class="icons icon-grid"></i><span>' + translate.dashboard + '</span></a></li>';
                                    break;
                                case 'homework':
                                    // Show homework menu item for all roles if it's assigned
                                    var homeworkUrl = (roleId === 7 || roleId === 6) ? 'userrole/homework' : 'homework';
                                    menuHtml += '<li><a href="' + base_url + homeworkUrl + '"><i class="icons icon-note"></i><span>' + translate.homework + '</span></a></li>';
                                    break;
                                case 'attendance':
                                    if (roleId === 7 || roleId === 6) {
                                        menuHtml += '<li><a href="' + base_url + 'userrole/attendance"><i class="icons icon-chart"></i><span>' + translate.attendance + '</span></a></li>';
                                    } else {
                                        menuHtml += '<li><a href="' + base_url + 'attendance"><i class="icons icon-chart"></i><span>' + translate.attendance + '</span></a></li>';
                                    }
                                    break;
                                case 'fees':
                                    // Show fees menu item for all roles if it's assigned
                                    var feesUrl = (roleId === 7 || roleId === 6) ? 'userrole/invoice' : 'fees/invoice_list';
                                    menuHtml += '<li><a href="' + base_url + feesUrl + '"><i class="icons icon-calculator"></i><span>' + translate.fees + '</span></a></li>';
                                    break;
                                case 'students':
                                    if (roleId !== 7 && roleId !== 6) {
                                        menuHtml += '<li><a href="' + base_url + 'student/view"><i class="icon-graduation icons"></i><span>' + translate.students + '</span></a></li>';
                                    }
                                    break;
                                case 'payments':
                                    if (roleId !== 7 && roleId !== 6) {
                                        menuHtml += '<li><a href="' + base_url + 'fees/invoice_list"><i class="fab fa-wpforms"></i><span>' + translate.payments + '</span></a></li>';
                                    }
                                    break;
                                case 'message':
                                    menuHtml += '<li><a href="' + base_url + 'communication/mailbox/inbox"><i class="icons icon-envelope-open"></i><span>' + translate.message + '</span></a></li>';
                                    break;
                            }
                        });
                        
                        // Add menu HTML to footer
                        $('.mobile-footer-menu').html(menuHtml);
                        
                        // Check if "More" menu already exists
                        if ($('.mobile-footer-menu li.more-menu').length === 0) {
                            // Add "More" menu option
                            var moreMenuItem = '<li class="more-menu">' +
                                '<a href="#" id="mobile-more-menu">' +
                                '<i class="fas fa-ellipsis-h"></i>' +
                                '<span>More</span>' +
                                '</a>' +
                                '</li>';
                            
                            // Append to mobile footer menu
                            $('.mobile-footer-menu').append(moreMenuItem);
                            
                            // Add click event to toggle sidebar
                            $('#mobile-more-menu').off('click').on('click', function(e) {
                                e.preventDefault();
                                
                                // Toggle sidebar
                                $('html').toggleClass('sidebar-left-opened');
                            });
                        }
                        
                        // Set active menu item
                        setActiveMenuItem();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading menu items:', error);
                    }
                });
            }
        }
    }
    
    // Function to set active menu item
    function setActiveMenuItem() {
        var currentPath = window.location.pathname;
        $('.mobile-footer-menu li a').each(function() {
            var linkPath = $(this).attr('href');
            if (currentPath.indexOf(linkPath) !== -1) {
                $(this).addClass('active');
            }
        });
    }
    
    // Function to ensure the footer is properly positioned
    function ensureFooterPosition() {
        // Check if we're on mobile
        if (window.innerWidth <= 767) {
            // Ensure body has proper padding
            $('body').css('padding-bottom', '80px');
            
            // Ensure the footer is fixed at the bottom
            $('.mobile-footer').css({
                'position': 'fixed',
                'bottom': '0',
                'left': '0',
                'right': '0',
                'width': '100%',
                'z-index': '1000',
                'transform': 'translateZ(0)' // Force hardware acceleration
            });
            
            // Check if More menu exists, if not add it
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
        }
    }
    
    // Initialize on document ready
    $(document).ready(function() {
        // Add body classes based on user type
        if (typeof user_type !== 'undefined') {
            switch (user_type) {
                case 'student':
                    $('body').addClass('student-logged-in');
                    break;
                case 'parent':
                    $('body').addClass('parent-logged-in');
                    break;
                case 'teacher':
                    $('body').addClass('teacher-logged-in');
                    break;
                case 'admin':
                    $('body').addClass('admin-logged-in');
                    break;
                case 'superadmin':
                    $('body').addClass('superadmin-logged-in');
                    break;
            }
        }
        
        // Reload mobile footer on page load
        reloadMobileFooter();
        
        // Ensure footer position
        ensureFooterPosition();
        
        // Run every 500ms for the first 5 seconds
        var positionInterval = setInterval(function() {
            ensureFooterPosition();
        }, 500);
        
        // Clear interval after 5 seconds
        setTimeout(function() {
            clearInterval(positionInterval);
            
            // Continue checking less frequently
            setInterval(function() {
                ensureFooterPosition();
            }, 2000);
        }, 5000);
        
        // Run on window resize
        $(window).resize(function() {
            ensureFooterPosition();
        });
        
        // Run on window scroll
        $(window).scroll(function() {
            ensureFooterPosition();
        });
        
        // Run after AJAX requests complete
        $(document).ajaxComplete(function() {
            ensureFooterPosition();
        });
        
        // Add refresh button for testing
        if ($('.mobile-footer').length && (user_type === 'admin' || user_type === 'superadmin')) {
            var $refreshButton = $('<button>', {
                'class': 'mobile-footer-refresh md-fab md-ripple',
                'html': '<i class="fas fa-sync-alt"></i>',
                'title': 'Refresh Footer Menu'
            }).css({
                'position': 'fixed',
                'bottom': '90px',
                'right': '16px',
                'z-index': '1001',
                'background': 'var(--md-primary, #6750A4)',
                'color': 'var(--md-on-primary, #fff)',
                'border': 'none',
                'border-radius': '50%',
                'width': '56px',
                'height': '56px',
                'display': 'flex',
                'align-items': 'center',
                'justify-content': 'center',
                'box-shadow': 'var(--md-elevation-level3, 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05))',
                'transition': 'background-color 0.2s, box-shadow 0.2s, transform 0.2s'
            }).on('click', function() {
                $(this).css('transform', 'rotate(360deg)');
                reloadMobileFooter();
                
                // Show Material Design snackbar if available
                if (typeof MaterialDesign !== 'undefined' && typeof MaterialDesign.showSnackbar === 'function') {
                    MaterialDesign.showSnackbar('Footer menu refreshed');
                }
                
                // Reset rotation after animation completes
                setTimeout(function() {
                    $refreshButton.css('transform', 'rotate(0deg)');
                }, 300);
            });
            
            $('body').append($refreshButton);
        }
    });
    
})(jQuery);