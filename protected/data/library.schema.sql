
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `ab` (
  `author` int(11) NOT NULL,
  `book` int(11) NOT NULL,
  PRIMARY KEY (`author`,`book`),
  KEY `book` (`book`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `authors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created` int(11) NOT NULL,
  `updated` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `created` int(11) NOT NULL,
  `updated` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `rb` (
  `reader` int(11) NOT NULL,
  `book` int(11) NOT NULL,
  PRIMARY KEY (`reader`,`book`),
  KEY `book` (`book`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `readers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created` int(11) NOT NULL,
  `updated` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `ab`
  ADD CONSTRAINT `ab_ibfk_4` FOREIGN KEY (`book`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ab_ibfk_3` FOREIGN KEY (`author`) REFERENCES `authors` (`id`) ON DELETE CASCADE;

ALTER TABLE `rb`
  ADD CONSTRAINT `rb_ibfk_4` FOREIGN KEY (`book`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rb_ibfk_3` FOREIGN KEY (`reader`) REFERENCES `readers` (`id`) ON DELETE CASCADE;
