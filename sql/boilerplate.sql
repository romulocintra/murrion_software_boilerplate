-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 15-03-2013 a las 10:51:10
-- Versión del servidor: 5.5.16
-- Versión de PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `boilerplate`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mur_email_sent`
--

CREATE TABLE `mur_email_sent` (
  `email_sent_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_sent_from` varchar(500) DEFAULT '',
  `email_sent_to` varchar(500) DEFAULT '',
  `email_sent_subject` varchar(500) DEFAULT '',
  `email_sent_text` mediumtext,
  `email_sent_bcc` varchar(500) DEFAULT '',
  `email_sent_cco` varchar(500) DEFAULT '',
  `email_sent_debugger` mediumtext NOT NULL,
  `email_sent_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`email_sent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
