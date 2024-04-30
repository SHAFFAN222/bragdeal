ALTER TABLE tickets
ADD COLUMN subject VARCHAR(255),
ADD COLUMN department VARCHAR(255),
ADD COLUMN priority VARCHAR(255);
INSERT INTO `roles` (`id`, `name`) VALUES (NULL, 'superadmin');
INSERT INTO `roles` (`id`, `name`) VALUES (NULL, 'client');
ALTER TABLE `article` ADD `category` VARCHAR(100) NOT NULL AFTER `create_at`;
ALTER TABLE `projects` ADD `description` TEXT NOT NULL AFTER `created_at`;
ALTER TABLE `projects` CHANGE `description` `description` 
TEXT CHARACTER SET 
utf8mb4 COLLATE utf8mb4_general_ci
 NULL DEFAULT NULL;
CREATE TABLE`invoice`( 
  `id` int(11) NOT NULL,
 `client_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL, 
  `price` double NOT NULL,
  `created_at` datetime NOT NULL,
   `update_at` datetime NOT NULL
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
--
INSERT INTO `invoice` (`id`, `client_id`, `project_id`, `price`, `created_at`, `updated_at`) VALUES ('1', '4', '5', '5000', current_timestamp(), current_timestamp());
ALTER TABLE `portal_settings` ADD `update_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_at`;
INSERT INTO `portal_settings` (`id`, `user_id`, `title`, `email`, `phone`, `address`, `color_scheme`, `logo`, `create_at`) VALUES (NULL, '5', 'daniyal', 'DANIYAL', '565532', 'KARACHI', 'RED', 'visa.png', current_timestamp());
