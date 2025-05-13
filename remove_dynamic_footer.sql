-- Drop the footer_menu_config table
DROP TABLE IF EXISTS `footer_menu_config`;

-- Drop the role_footer_mapping table if it exists
DROP TABLE IF EXISTS `role_footer_mapping`;

-- Remove any related permissions
DELETE FROM `permission` WHERE `prefix` LIKE 'footer_%';

-- Remove any related cache entries (if using file cache)
-- Note: This is handled by the application's cache system 