CREATE TABLE `region`
(
    `id`   int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(128)     NOT NULL,
    `slug` varchar(128)     NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_voivodeship_slug` (`slug`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_polish_ci;

CREATE TABLE `county`
(
    `id`        int(10) unsigned NOT NULL AUTO_INCREMENT,
    `region_id` int(10) unsigned NOT NULL,
    `name`      varchar(128)     NOT NULL,
    `slug`      varchar(128)     NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_county_region_slug` (`region_id`, `slug`),
    KEY `idx_county_region` (`region_id`),
    CONSTRAINT `fk_county_region` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_polish_ci;

CREATE TABLE `image`
(
    `id`          int(10) unsigned NOT NULL AUTO_INCREMENT,
    `county_id`   int(10) unsigned NOT NULL,
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
    KEY `idx_image_county` (`county_id`),
    KEY `idx_image_ext` (`ext`),
    CONSTRAINT `fk_image_county` FOREIGN KEY (`county_id`) REFERENCES `county` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_polish_ci;
