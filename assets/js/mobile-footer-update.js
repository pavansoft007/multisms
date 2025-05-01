/**
 * Mobile Footer Update Script
 * This script updates the mobile footer based on user role and settings
 */
$(document).ready(function() {
    // Function to update the "More" button
    function updateMoreButton() {
        // Find all "More" buttons in the mobile footer
        $('.mobile-footer-menu li.more-menu a, #mobile-more-menu').each(function() {
            // Remove existing click events
            $(this).off('click');
            
            // Update href to point to mobilemain
            $(this).attr('href', base_url + 'mobilemain');
            
            // Add click event to navigate to mobilemain
            $(this).on('click', function(e) {
                // Only prevent default if we're already on mobilemain
                if (window.location.pathname.indexOf('mobilemain') !== -1) {
                    e.preventDefault();
                    // You could add additional functionality here if needed
                }
            });
        });
    }
    
    // Run on page load
    updateMoreButton();
    
    // Also run after any AJAX requests that might refresh the footer
    $(document).ajaxComplete(function() {
        setTimeout(updateMoreButton, 100);
    });
    
    // Check if we're on a mobile device
    if (isMobileDevice()) {
        // Get the current user role
        var userRole = getUserRole();
        
        // Update the footer based on role settings
        updateFooterItems(userRole);
    }
});

/**
 * Check if the current device is mobile
 * @returns {boolean} True if mobile device
 */
function isMobileDevice() {
    // Check if the viewport width is less than 768px
    if (window.innerWidth <= 767) {
        return true;
    }
    
    // Check for mobile user agent
    var userAgent = navigator.userAgent || navigator.vendor || window.opera;
    if (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(userAgent.toLowerCase())) {
        return true;
    }
    
    return false;
}

/**
 * Get the current user role from the page
 * @returns {string} User role ID
 */
function getUserRole() {
    // Try to get role from data attribute or hidden field
    var roleId = $('body').data('role-id') || $('#user_role_id').val();
    
    // If not found, default to a standard role
    if (!roleId) {
        roleId = '1'; // Default role
    }
    
    return roleId;
}

/**
 * Update the footer items based on role settings
 * @param {string} roleId The user role ID
 */
function updateFooterItems(roleId) {
    // Only proceed if we have a mobile footer
    if ($('.mobile-footer').length === 0) {
        return;
    }
    
    // Get the card items for this role via AJAX
    $.ajax({
        url: base_url + 'mobilemain/get_card_items',
        type: 'POST',
        data: {
            role_id: roleId
        },
        dataType: 'json',
        success: function(response) {
            // If we have selected items, update the footer
            if (response.selected_items && response.selected_items.length > 0) {
                console.log('Mobile cards loaded:', response.selected_items);
                // Here you would update the footer UI based on the selected items
                // This implementation depends on your specific footer structure
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading footer items:', error);
        }
    });
}