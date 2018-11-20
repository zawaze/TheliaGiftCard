
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- gift_card
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `gift_card`;

CREATE TABLE `gift_card`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `sponsor_customer_id` INTEGER NOT NULL,
    `code` VARCHAR(100) NOT NULL,
    `amount` DECIMAL(16,6),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `FI_card_gift_sponsor_customer` (`sponsor_customer_id`),
    CONSTRAINT `fk_card_gift_sponsor_customer`
        FOREIGN KEY (`sponsor_customer_id`)
        REFERENCES `customer` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- gift_card_customer
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `gift_card_customer`;

CREATE TABLE `gift_card_customer`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `customer_id` INTEGER NOT NULL,
    `card_id` INTEGER(50) NOT NULL,
    `used_amount` DECIMAL(16,6),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `FI_card_gift_customer` (`customer_id`),
    CONSTRAINT `fk_card_gift_customer`
        FOREIGN KEY (`customer_id`)
        REFERENCES `customer` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
