-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2013 年 09 月 27 日 08:40
-- 服务器版本: 5.6.11
-- PHP 版本: 5.3.26

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `tang`
--

-- --------------------------------------------------------

--
-- 表的结构 `area`
--

DROP TABLE IF EXISTS `area`;
CREATE TABLE IF NOT EXISTS `area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `county_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `area`
--

INSERT INTO `area` (`id`, `name`, `county_id`) VALUES
(1, '上海市场', 1),
(2, '广州市场', 1),
(3, '新都汇', 2),
(4, '万达广场', 1),
(5, '谷水', 1);

-- --------------------------------------------------------

--
-- 表的结构 `county`
--

DROP TABLE IF EXISTS `county`;
CREATE TABLE IF NOT EXISTS `county` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '0 区；1 县',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `county`
--

INSERT INTO `county` (`id`, `name`, `type`) VALUES
(1, '涧西区', 0),
(2, '西工区', 0),
(3, '老城区', 0),
(4, '洛龙区', 0);

-- --------------------------------------------------------

--
-- 表的结构 `restaurant`
--

DROP TABLE IF EXISTS `restaurant`;
CREATE TABLE IF NOT EXISTS `restaurant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `phone` varchar(64) NOT NULL DEFAULT '',
  `business_hour` varchar(128) NOT NULL DEFAULT '6:00 - 20:00',
  `address` varchar(128) NOT NULL,
  `county_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属县、区',
  `area_id` int(11) NOT NULL DEFAULT '0' COMMENT '商圈范围。',
  `is_shutdown` tinyint(4) NOT NULL DEFAULT '0',
  `image_url` varchar(256) NOT NULL DEFAULT '',
  `latitude` double NOT NULL DEFAULT '0',
  `longitude` double NOT NULL DEFAULT '0',
  `description` varchar(256) NOT NULL DEFAULT '好地方。',
  `votes` int(11) NOT NULL DEFAULT '0' COMMENT '投票总数',
  `average_points` float NOT NULL DEFAULT '0' COMMENT '平均分',
  `weighted_points` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- 转存表中的数据 `restaurant`
--

INSERT INTO `restaurant` (`id`, `name`, `phone`, `business_hour`, `address`, `county_id`, `area_id`, `is_shutdown`, `image_url`, `latitude`, `longitude`, `description`, `votes`, `average_points`, `weighted_points`) VALUES
(1, '大石桥羊肉汤', '138', '6:00 - 12:00', '建设路24号', 1, 2, 2, '/images/restaurant/profile_1.png', 0, 0, '好喝', 2, 5.5, 1.70833),
(2, '清真牛肉汤', '', '6:00 - 20:00', '涧西区西苑路2号', 1, 1, 0, '/images/restaurant/profile_2.png', 0, 0, '好地方。', 1, 4, 1.22727),
(3, '怀府驴肉汤', '15038595869', '6:00 - 20:00', '洛阳市西工区健康路8号', 2, 1, 0, '/images/restaurant/profile_3.png', 0, 0, '好地方。', 0, 0, 0.95),
(4, '白沙羊肉汤', '15038595869', '6:00 - 20:00', '洛阳市西工区七一路30号', 4, 1, 1, '/images/restaurant/profile_4.jpg', 0, 0, '好地方。', 0, 0, 0.95),
(5, '大众牛肉汤', '15038595869', '6:00 - 20:00', '洛阳市洛龙区龙翔路99号', 1, 1, 0, '', 0, 0, '好地方。', 0, 0, 0.95),
(6, '大众牛肉汤', '15038595869', '6:00 - 20:00', '洛阳市涧西区湖北路87号', 0, 0, 0, '', 0, 0, '好地方。', 0, 0, 0.95),
(7, '百碗羊汤', '1', '6:00 - 20:00', '洛阳市西工区解放路80号', 0, 0, 0, '', 0, 0, '好地方。', 0, 0, 0.95),
(8, '百碗羊汤', '15038595869', '6:00 - 20:00', '洛阳市老城区金业路11号', 0, 0, 0, '', 0, 0, '好地方。', 0, 0, 0.95),
(9, '桥头豆腐汤', '15038595869', '6:00 - 20:00', '洛阳市西工区七一路11号', 0, 0, 0, '', 0, 0, '好地方。', 0, 0, 0.95),
(10, '赵记丸子汤', '15038595869', '6:00 - 20:00', '洛阳市老城区民主街881号', 0, 0, 0, '', 0, 0, '好地方。', 0, 0, 0.95),
(11, '大众牛肉汤', '', '6:00 - 20:00', '西工区九都路98号', 0, 0, 0, '', 0, 0, '好地方。', 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `restaurant_status`
--

DROP TABLE IF EXISTS `restaurant_status`;
CREATE TABLE IF NOT EXISTS `restaurant_status` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `restaurant_status`
--

INSERT INTO `restaurant_status` (`id`, `name`) VALUES
(0, '正常营业'),
(1, '临时关闭'),
(2, '永久关闭');

-- --------------------------------------------------------

--
-- 表的结构 `vote`
--

DROP TABLE IF EXISTS `vote`;
CREATE TABLE IF NOT EXISTS `vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL COMMENT '1-10分',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- 转存表中的数据 `vote`
--

INSERT INTO `vote` (`id`, `user_id`, `restaurant_id`, `rating`) VALUES
(15, 1, 1, 6),
(16, 1, 2, 4),
(17, 4, 1, 5);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
