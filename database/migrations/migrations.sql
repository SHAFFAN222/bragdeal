ALTER TABLE tickets
ADD COLUMN subject VARCHAR(255),
ADD COLUMN department VARCHAR(255),
ADD COLUMN priority VARCHAR(255);

INSERT INTO `roles` (`id`, `name`) VALUES (NULL, 'superadmin');
INSERT INTO `roles` (`id`, `name`) VALUES (NULL, 'client');


