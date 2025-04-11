// Mobile device detector
(function() {
    function detectMobileDevice() {
        var isMobile = false;
        
        // Check screen width
        if (window.innerWidth <= 767) {
            isMobile = true;
        }
        
        // Set cookie for server-side detection
        document.cookie = "is_mobile=" + isMobile + "; path=/; max-age=3600";
        
        return isMobile;
    }
    
    // Run on page load
    detectMobileDevice();
    
    // Run on window resize
    window.addEventListener('resize', function() {
        detectMobileDevice();
    });
})();