CREATE TABLE `messages`
(
    `id`        INT          NOT NULL AUTO_INCREMENT,
    `well`      VARCHAR(10)  NOT NULL,
    `equipment` VARCHAR(125) NOT NULL,
    `product`   VARCHAR(125) NOT NULL,
    `failure`   VARCHAR(125) NOT NULL,
    `sent`      TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE `requests`
(
    `id`         INT          NOT NULL AUTO_INCREMENT,
    `message_id` INT          NOT NULL,
    `well`       VARCHAR(10)  NOT NULL,
    `equipment`  VARCHAR(125) NOT NULL,
    `product`    VARCHAR(125) NOT NULL,
    `failure`    VARCHAR(125) NOT NULL,
    `sent`       TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE `orders`
(
    `id`            INT          NOT NULL AUTO_INCREMENT,
    `message_id`    INT          NOT NULL,
    `request_id`    INT          NOT NULL,
    `delivery_date` DATE         NOT NULL,
    `accepted`      TINYINT(1)   NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;
