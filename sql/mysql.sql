CREATE TABLE `tdmspot_newblocks` (
  `id`      MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `bid`     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
  `pid`     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
  `options` VARCHAR(255)          NOT NULL DEFAULT '',
  `title`   VARCHAR(255)          NOT NULL DEFAULT '',
  `side`    VARCHAR(255)          NOT NULL DEFAULT '',
  `weight`  SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
  `visible` TINYINT(1) UNSIGNED   NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
  ENGINE = MyISAM;

CREATE TABLE `tdmspot_page` (
  `id`      MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`   VARCHAR(255)          NOT NULL DEFAULT '',
  `weight`  SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
  `visible` TINYINT(1) UNSIGNED   NOT NULL DEFAULT '0',
  `cat`     TEXT,
  `limit`   SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
  ENGINE = MyISAM;

CREATE TABLE `tdmspot_cat` (
  `id`      INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid`     INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `title`   VARCHAR(50)      NOT NULL DEFAULT '',
  `date`    INT(11)          NOT NULL DEFAULT '0',
  `text`    TEXT,
  `img`     VARCHAR(100)              DEFAULT NULL,
  `weight`  INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `display` INT(1)           NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
  ENGINE = MyISAM;

CREATE TABLE `tdmspot_item` (
  `id`       MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cat`      INT(10) UNSIGNED      NOT NULL DEFAULT '0',
  `title`    VARCHAR(50)           NOT NULL DEFAULT '',
  `text`     TEXT,
  `display`  INT(1)                NOT NULL DEFAULT '0',
  `file`     TEXT,
  `indate`   INT(10) UNSIGNED      NOT NULL DEFAULT '0',
  `hits`     INT(10) UNSIGNED      NOT NULL DEFAULT '0',
  `votes`    INT(10) UNSIGNED      NOT NULL DEFAULT '0',
  `counts`   INT(10) UNSIGNED      NOT NULL DEFAULT '0',
  `comments` INT(11) UNSIGNED      NOT NULL DEFAULT '0',
  `poster`   INT(10) UNSIGNED      NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
  ENGINE = MyISAM;

CREATE TABLE `tdmspot_vote` (
  `vote_id`      INT(8) UNSIGNED  NOT NULL AUTO_INCREMENT,
  `vote_file`    INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `vote_album`   INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `vote_artiste` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `vote_ip`      VARCHAR(20)               DEFAULT NULL,
  PRIMARY KEY (`vote_id`)
)
  ENGINE = MyISAM;
