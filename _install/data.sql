-- --------------------------------------------------------
-- Host:                         mysql302.1gb.ua
-- Server version:               5.1.51-log - Gentoo Linux mysql-5.1.51
-- Server OS:                    pc-linux-gnu
-- HeidiSQL version:             7.0.0.4053
-- Date/time:                    2013-02-16 00:55:17
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping database structure for gbua_nvt_db
CREATE DATABASE IF NOT EXISTS `gbua_nvt_db` /*!40100 DEFAULT CHARACTER SET cp1251 */;
USE `gbua_nvt_db`;


-- Dumping structure for table gbua_nvt_db.qr_manager_file_storage
CREATE TABLE IF NOT EXISTS `qr_manager_file_storage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_link` text,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- Data exporting was unselected.


-- Dumping structure for table gbua_nvt_db.qr_manager_issue
CREATE TABLE IF NOT EXISTS `qr_manager_issue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `summary` text NOT NULL,
  `description` longtext NOT NULL,
  `description_2` longtext NOT NULL,
  `menu` int(11) DEFAULT '0',
  `parent_issue_id` int(11) DEFAULT '-1',
  `lang` int(11) NOT NULL DEFAULT '1',
  `img_1` text NOT NULL,
  `img_2` text NOT NULL,
  `img_3` text NOT NULL,
  `img_arr` longtext NOT NULL,
  `file_arr` longtext NOT NULL,
  `order_by` int(11) NOT NULL,
  `css_class` int(11) NOT NULL,
  `css_id` int(11) NOT NULL,
  `tags` text NOT NULL,
  `php_file` longtext NOT NULL,
  `css_file` text NOT NULL,
  `is_visible` int(1) DEFAULT '1',
  `properties` longtext,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- Data exporting was unselected.


-- Dumping structure for table gbua_nvt_db.qr_manager_issue_properties
CREATE TABLE IF NOT EXISTS `qr_manager_issue_properties` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` text,
  `type` text,
  `field_name` text,
  `value` text,
  `description` text,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- Data exporting was unselected.


-- Dumping structure for table gbua_nvt_db.qr_manager_issue_properties_data
CREATE TABLE IF NOT EXISTS `qr_manager_issue_properties_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `issue_id` int(11) DEFAULT NULL,
  `property_id` int(11) DEFAULT NULL,
  `data_value` tinytext,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.


-- Dumping structure for table gbua_nvt_db.qr_manager_main_menu
CREATE TABLE IF NOT EXISTS `qr_manager_main_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `chapter` text,
  `is_sub_menu` int(11) DEFAULT '-1',
  `lang` int(11) NOT NULL,
  `order_by` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `img_1` text NOT NULL,
  `img_2` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `html_title` text NOT NULL,
  `is_visible` int(1) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- Data exporting was unselected.


-- Dumping structure for table gbua_nvt_db.qr_manager_users
CREATE TABLE IF NOT EXISTS `qr_manager_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` text NOT NULL,
  `name` text NOT NULL,
  `pass` text NOT NULL,
  `mail` text NOT NULL,
  `skype` text NOT NULL,
  `icq` text NOT NULL,
  `phone` text NOT NULL,
  `personal_data` longtext NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- Data exporting was unselected.
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
