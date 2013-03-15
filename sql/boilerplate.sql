/*CREATE DATABASE IF NOT EXISTS `boilerplate` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;*/

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Table structure for `mur_captcha`
--

CREATE TABLE `mur_captcha` (
  `captcha_id` bigint(13) unsigned NOT NULL AUTO_INCREMENT,
  `captcha_time` int(10) unsigned NOT NULL,
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `word` varchar(20) NOT NULL,
  PRIMARY KEY (`captcha_id`),
  KEY `word` (`word`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for `mur_email_sent`
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

-- --------------------------------------------------------

--
-- Table structure for `mur_user`
--

CREATE TABLE `mur_user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_type` varchar(20) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_last_name` varchar(100) NOT NULL DEFAULT '',
  `user_password` varchar(32) NOT NULL,
  `user_facebook_id` varchar(20) DEFAULT NULL,
  `user_activation_token` varchar(32) NOT NULL,
  `user_recovery_token` varchar(32) NOT NULL,
  `user_login_attempts` smallint(5) unsigned NOT NULL DEFAULT '0',
  `user_email_updates` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `user_approved` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'used only for firm solicitors',
  `user_active` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `user_retired` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `user_deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `user_last_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for `mur_user_login`
--

CREATE TABLE `mur_user_login` (
  `user_login_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `user_login_useragent` varchar(255) DEFAULT '',
  `user_login_ip` varchar(15) DEFAULT '',
  `user_login_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_login_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
