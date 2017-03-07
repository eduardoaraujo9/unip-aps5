SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `cliente` int(10) NOT NULL,
  `hora` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tipo` int(1) DEFAULT '0',
  `dados` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente` (`cliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nome` varchar(64) NOT NULL,
  `email` varchar(128) NOT NULL,
  `senha` varchar(32) DEFAULT NULL,
  `lastupdate` varchar(64) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(10) NOT NULL,
  `tema` int(1) DEFAULT '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `tokens` (
  `id` int(10) NOT NULL,
  `token` varchar(16) NOT NULL,
  `validade` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`token`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`id`);

ALTER TABLE `config`
  ADD CONSTRAINT `config_ibfk_1` FOREIGN KEY (`id`) REFERENCES `clientes` (`id`);

ALTER TABLE `tokens`
  ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`id`) REFERENCES `clientes` (`id`);

