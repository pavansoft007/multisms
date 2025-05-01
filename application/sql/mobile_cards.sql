-- SQL script to create mobile_cards_config table
CREATE TABLE IF NOT EXISTS `mobile_cards_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `card_item` varchar(50) NOT NULL,
  `card_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insert default card configurations for each role
-- Role ID 1: Superadmin
INSERT INTO `mobile_cards_config` (`role_id`, `card_item`, `card_order`, `status`) VALUES
(1, 'students', 1, 1),
(1, 'attendance', 2, 1),
(1, 'fees', 3, 1),
(1, 'classes', 4, 1),
(1, 'homework', 5, 1),
(1, 'marks', 6, 1),
(1, 'events', 7, 1),
(1, 'reports', 8, 1),
(1, 'profile', 9, 1),
(1, 'settings', 10, 1);

-- Role ID 2: Admin
INSERT INTO `mobile_cards_config` (`role_id`, `card_item`, `card_order`, `status`) VALUES
(2, 'students', 1, 1),
(2, 'attendance', 2, 1),
(2, 'fees', 3, 1),
(2, 'classes', 4, 1),
(2, 'homework', 5, 1),
(2, 'marks', 6, 1),
(2, 'events', 7, 1),
(2, 'reports', 8, 1),
(2, 'profile', 9, 1),
(2, 'settings', 10, 1);

-- Role ID 3: Teacher
INSERT INTO `mobile_cards_config` (`role_id`, `card_item`, `card_order`, `status`) VALUES
(3, 'students', 1, 1),
(3, 'attendance', 2, 1),
(3, 'classes', 3, 1),
(3, 'homework', 4, 1),
(3, 'marks', 5, 1),
(3, 'events', 6, 1),
(3, 'profile', 7, 1),
(3, 'settings', 8, 1);

-- Role ID 6: Parent
INSERT INTO `mobile_cards_config` (`role_id`, `card_item`, `card_order`, `status`) VALUES
(6, 'attendance', 1, 1),
(6, 'fees', 2, 1),
(6, 'homework', 3, 1),
(6, 'marks', 4, 1),
(6, 'events', 5, 1),
(6, 'profile', 6, 1),
(6, 'settings', 7, 1);

-- Role ID 7: Student
INSERT INTO `mobile_cards_config` (`role_id`, `card_item`, `card_order`, `status`) VALUES
(7, 'attendance', 1, 1),
(7, 'fees', 2, 1),
(7, 'homework', 3, 1),
(7, 'marks', 4, 1),
(7, 'events', 5, 1),
(7, 'profile', 6, 1),
(7, 'settings', 7, 1);