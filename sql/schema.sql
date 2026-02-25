CREATE TABLE `category`
(
    `id`             int(10) unsigned NOT NULL AUTO_INCREMENT,
    `parent_id`      int(10) unsigned DEFAULT NULL,
    `directory_name` varchar(128)     NOT NULL,
    `url_slug`       varchar(128)     NOT NULL,
    `label`          varchar(128)     NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_category_parent_slug` (`parent_id`, `url_slug`),
    UNIQUE KEY `uq_category_parent_directory_name` (`parent_id`, `directory_name`),
    KEY `idx_category_parent` (`parent_id`),
    CONSTRAINT `fk_category_parent` FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_polish_ci;

INSERT INTO `category` (id, directory_name, url_slug, label)
VALUES (1, 'root', 'root', 'root');

CREATE TABLE `image`
(
    `id`          int(10) unsigned NOT NULL AUTO_INCREMENT,
    `category_id` int(10) unsigned NOT NULL,
    `filename`    varchar(255)     NOT NULL,
    `real_path`   varchar(512)     NOT NULL,
    `ext`         varchar(8)       NOT NULL,
    `location`    varchar(255)              DEFAULT NULL,
    `years`       varchar(64)               DEFAULT NULL,
    `dimensions`  varchar(64)               DEFAULT NULL,
    `description` varchar(2000)             DEFAULT NULL,
    `gccode`      varchar(100)              DEFAULT NULL,
    `created_at`  timestamp        NOT NULL DEFAULT current_timestamp(),
    `updated_at`  timestamp        NULL     DEFAULT NULL ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_image_real_path` (`real_path`),
    KEY `idx_image_category` (`category_id`),
    KEY `idx_image_ext` (`ext`),
    CONSTRAINT `fk_image_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_polish_ci;
