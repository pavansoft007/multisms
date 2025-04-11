-- Add footer settings permission
INSERT INTO permission (module_id, name, prefix) 
VALUES (
    (SELECT id FROM permission_modules WHERE name = 'settings'), 
    'Footer Settings', 
    'footer_settings'
);